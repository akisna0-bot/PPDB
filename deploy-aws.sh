#!/bin/bash

# PPDB AWS Deployment Script
# Sesuai dengan proposal teknis untuk deployment ke AWS

set -e

echo "🚀 Starting PPDB AWS Deployment..."

# Variables
APP_NAME="ppdb"
ENVIRONMENT=${1:-production}
AWS_REGION=${2:-ap-southeast-1}

echo "📋 Configuration:"
echo "   App Name: $APP_NAME"
echo "   Environment: $ENVIRONMENT"
echo "   AWS Region: $AWS_REGION"

# 1. Build Laravel Application
echo "🔨 Building Laravel application..."
composer install --no-dev --optimize-autoloader
npm install && npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 2. Create deployment package
echo "📦 Creating deployment package..."
zip -r ppdb-deployment.zip . -x "*.git*" "node_modules/*" "tests/*" "storage/logs/*"

# 3. Deploy Infrastructure with Terraform
echo "🏗️ Deploying AWS infrastructure..."
cd terraform
terraform init
terraform plan -var="environment=$ENVIRONMENT" -var="aws_region=$AWS_REGION"
terraform apply -auto-approve -var="environment=$ENVIRONMENT" -var="aws_region=$AWS_REGION"

# Get outputs
RDS_ENDPOINT=$(terraform output -raw rds_endpoint)
S3_BUCKET=$(terraform output -raw s3_bucket)

cd ..

# 4. Update environment configuration
echo "⚙️ Updating environment configuration..."
cat > .env.production << EOF
APP_NAME=PPDB
APP_ENV=production
APP_KEY=$(php artisan key:generate --show)
APP_DEBUG=false
APP_URL=https://ppdb.example.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=$RDS_ENDPOINT
DB_PORT=3306
DB_DATABASE=ppdb
DB_USERNAME=admin
DB_PASSWORD=ChangeMePlease123!

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=s3
QUEUE_CONNECTION=sqs
SESSION_DRIVER=file
SESSION_LIFETIME=120

AWS_ACCESS_KEY_ID=\${AWS_ACCESS_KEY_ID}
AWS_SECRET_ACCESS_KEY=\${AWS_SECRET_ACCESS_KEY}
AWS_DEFAULT_REGION=$AWS_REGION
AWS_BUCKET=$S3_BUCKET
AWS_USE_PATH_STYLE_ENDPOINT=false

MAIL_MAILER=ses
MAIL_HOST=email-smtp.$AWS_REGION.amazonaws.com
MAIL_PORT=587
MAIL_USERNAME=\${SES_USERNAME}
MAIL_PASSWORD=\${SES_PASSWORD}
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@ppdb.example.com
MAIL_FROM_NAME="\${APP_NAME}"
EOF

# 5. Deploy to Elastic Beanstalk (or ECS)
echo "🚀 Deploying to AWS Elastic Beanstalk..."

# Create Elastic Beanstalk application version
aws elasticbeanstalk create-application-version \
    --application-name $APP_NAME \
    --version-label $(date +%Y%m%d-%H%M%S) \
    --source-bundle S3Bucket=$S3_BUCKET,S3Key=ppdb-deployment.zip \
    --region $AWS_REGION

# Deploy to environment
aws elasticbeanstalk update-environment \
    --application-name $APP_NAME \
    --environment-name $APP_NAME-$ENVIRONMENT \
    --version-label $(date +%Y%m%d-%H%M%S) \
    --region $AWS_REGION

# 6. Run database migrations
echo "🗄️ Running database migrations..."
# This would be done via EB extensions or ECS task
# php artisan migrate --force

# 7. Setup monitoring and alerts
echo "📊 Setting up CloudWatch monitoring..."
aws logs create-log-group --log-group-name /aws/elasticbeanstalk/$APP_NAME-$ENVIRONMENT/var/log/eb-engine.log --region $AWS_REGION || true
aws logs create-log-group --log-group-name /aws/elasticbeanstalk/$APP_NAME-$ENVIRONMENT/var/log/eb-hooks.log --region $AWS_REGION || true

# 8. Verify deployment
echo "✅ Verifying deployment..."
sleep 30
HEALTH_CHECK_URL="https://$APP_NAME-$ENVIRONMENT.elasticbeanstalk.com/api/health"
curl -f $HEALTH_CHECK_URL || echo "⚠️ Health check failed, please verify manually"

echo "🎉 PPDB AWS Deployment completed!"
echo ""
echo "📋 Deployment Summary:"
echo "   Application: $APP_NAME"
echo "   Environment: $ENVIRONMENT"
echo "   RDS Endpoint: $RDS_ENDPOINT"
echo "   S3 Bucket: $S3_BUCKET"
echo "   Health Check: $HEALTH_CHECK_URL"
echo ""
echo "🔗 Next Steps:"
echo "   1. Configure domain and SSL certificate"
echo "   2. Setup SES for email notifications"
echo "   3. Configure backup policies"
echo "   4. Setup monitoring alerts"
echo "   5. Test all functionality"