# PPDB AWS Deployment Guide

## Overview
Panduan deployment aplikasi PPDB (Penerimaan Peserta Didik Baru) ke AWS sesuai dengan proposal teknis yang telah dibuat.

## Architecture Summary
- **Frontend**: Laravel Blade (akan di-host di S3 + CloudFront untuk static assets)
- **Backend**: Laravel 10 (PHP 8.1+) di Elastic Beanstalk atau ECS/Fargate
- **Database**: Amazon RDS MySQL Multi-AZ
- **File Storage**: Amazon S3
- **Email**: Amazon SES
- **Monitoring**: CloudWatch + CloudTrail

## Prerequisites
1. AWS CLI configured dengan appropriate permissions
2. Terraform >= 1.0 installed
3. PHP 8.1+ dan Composer
4. Node.js untuk asset building

## Quick Deployment

### 1. Clone Repository
```bash
git clone <repository-url>
cd ppdb
```

### 2. Install Dependencies
```bash
composer install --no-dev --optimize-autoloader
npm install && npm run build
```

### 3. Deploy Infrastructure
```bash
cd terraform
terraform init
terraform plan
terraform apply
```

### 4. Deploy Application
```bash
chmod +x deploy-aws.sh
./deploy-aws.sh production ap-southeast-1
```

## Manual Deployment Steps

### Step 1: Infrastructure Provisioning

#### RDS Database
```bash
# Create RDS instance
aws rds create-db-instance \
    --db-instance-identifier ppdb-db \
    --db-instance-class db.t3.micro \
    --engine mysql \
    --engine-version 8.0 \
    --allocated-storage 20 \
    --db-name ppdb \
    --master-username admin \
    --master-user-password ChangeMePlease123! \
    --vpc-security-group-ids sg-xxxxxxxxx \
    --backup-retention-period 7 \
    --multi-az
```

#### S3 Bucket
```bash
# Create S3 bucket for file uploads
aws s3 mb s3://ppdb-uploads-$(date +%s)
aws s3api put-bucket-versioning \
    --bucket ppdb-uploads-$(date +%s) \
    --versioning-configuration Status=Enabled
```

#### Elastic Beanstalk
```bash
# Create EB application
aws elasticbeanstalk create-application \
    --application-name ppdb \
    --description "PPDB Application"

# Create environment
aws elasticbeanstalk create-environment \
    --application-name ppdb \
    --environment-name ppdb-production \
    --solution-stack-name "64bit Amazon Linux 2 v3.4.0 running PHP 8.1"
```

### Step 2: Application Configuration

#### Environment Variables
Set the following environment variables in Elastic Beanstalk:

```
APP_NAME=PPDB
APP_ENV=production
APP_DEBUG=false
DB_HOST=<rds-endpoint>
DB_DATABASE=ppdb
DB_USERNAME=admin
DB_PASSWORD=ChangeMePlease123!
AWS_BUCKET=<s3-bucket-name>
MAIL_MAILER=ses
```

#### Database Migration
```bash
# Run migrations (via EB extensions or manually)
php artisan migrate --force
php artisan db:seed --class=BasicSeeder
```

### Step 3: Security Configuration

#### IAM Roles
Create IAM role for EC2 instances with policies:
- `AmazonS3FullAccess` (scope to specific bucket)
- `AmazonSESFullAccess`
- `CloudWatchAgentServerPolicy`

#### Security Groups
- **Web Tier**: Allow HTTP (80) and HTTPS (443) from 0.0.0.0/0
- **Database Tier**: Allow MySQL (3306) from Web Tier only

### Step 4: Monitoring Setup

#### CloudWatch Alarms
```bash
# High CPU alarm
aws cloudwatch put-metric-alarm \
    --alarm-name "PPDB-HighCPU" \
    --alarm-description "High CPU utilization" \
    --metric-name CPUUtilization \
    --namespace AWS/EC2 \
    --statistic Average \
    --period 300 \
    --threshold 80 \
    --comparison-operator GreaterThanThreshold

# Database connections alarm
aws cloudwatch put-metric-alarm \
    --alarm-name "PPDB-HighDBConnections" \
    --alarm-description "High database connections" \
    --metric-name DatabaseConnections \
    --namespace AWS/RDS \
    --statistic Average \
    --period 300 \
    --threshold 40 \
    --comparison-operator GreaterThanThreshold
```

## API Endpoints

### Health Check
```
GET /api/health
```
Response:
```json
{
  "status": "healthy",
  "timestamp": "2024-01-01T00:00:00Z",
  "database": "connected",
  "version": "1.0.0"
}
```

### Dashboard Summary
```
GET /api/v1/dashboard/summary
```

### Map Data
```
GET /api/v1/map/data
```

## Workflow Integration

### Status Flow
1. **DRAFT** → **SUBMIT** (User submits application)
2. **SUBMIT** → **ADM_PASS/ADM_REJECT** (Admin verification)
3. **ADM_PASS** → **MENUNGGU_BAYAR** (Payment required)
4. **MENUNGGU_BAYAR** → **PAID** (Payment verified)
5. **PAID** → **LULUS/TIDAK_LULUS/CADANGAN** (Final decision)

### API Workflow Endpoints
```bash
# Verify applicant (Verifikator role)
POST /api/v1/workflow/verify-applicant/{applicant}
{
  "status": "ADM_PASS",
  "catatan_verifikasi": "Berkas lengkap"
}

# Verify payment (Keuangan role)
POST /api/v1/workflow/verify-payment/{payment}
{
  "status": "PAID",
  "catatan": "Pembayaran valid"
}

# Final decision (Kepala Sekolah role)
POST /api/v1/workflow/final-decision/{applicant}
{
  "status": "LULUS",
  "catatan": "Memenuhi kriteria"
}
```

## Backup & Recovery

### Database Backup
- Automated daily backups enabled
- 7-day retention period
- Point-in-time recovery available

### File Backup
- S3 versioning enabled
- Cross-region replication (optional)

## Scaling Configuration

### Auto Scaling
- Min instances: 1
- Max instances: 5
- Target CPU: 70%
- Scale-out cooldown: 300s
- Scale-in cooldown: 300s

### Database Scaling
- Read replicas for reporting queries
- Connection pooling configured

## Security Checklist

- [ ] HTTPS enforced (SSL certificate)
- [ ] Database encryption at rest
- [ ] S3 bucket private with proper IAM policies
- [ ] WAF configured for web application
- [ ] VPC with private subnets for database
- [ ] Security groups with least privilege
- [ ] CloudTrail logging enabled
- [ ] Regular security updates

## Monitoring & Alerts

### Key Metrics
- Application response time
- Database connection count
- Error rate (4xx, 5xx)
- Storage usage
- Memory utilization

### Log Aggregation
- Application logs → CloudWatch Logs
- Web server logs → CloudWatch Logs
- Database logs → CloudWatch Logs

## Cost Optimization

### Estimated Monthly Costs (ap-southeast-1)
- **RDS db.t3.micro**: ~$15
- **Elastic Beanstalk (t3.micro)**: ~$8
- **S3 storage (10GB)**: ~$0.25
- **CloudFront**: ~$1
- **SES (1000 emails)**: ~$0.10
- **Total**: ~$25/month

### Cost Optimization Tips
1. Use Reserved Instances for predictable workloads
2. Enable S3 Intelligent Tiering
3. Set up billing alerts
4. Regular cost reviews

## Support & Maintenance

### Regular Tasks
- [ ] Weekly security updates
- [ ] Monthly cost review
- [ ] Quarterly performance review
- [ ] Database maintenance windows

### Emergency Contacts
- AWS Support: [Support Case URL]
- Application Team: [Contact Info]
- Database Team: [Contact Info]

## Troubleshooting

### Common Issues

#### Application Won't Start
1. Check environment variables
2. Verify database connectivity
3. Check application logs in CloudWatch

#### High Response Times
1. Check CPU/Memory utilization
2. Review database performance
3. Check for slow queries

#### File Upload Issues
1. Verify S3 bucket permissions
2. Check IAM role policies
3. Validate file size limits

### Log Locations
- Application: `/var/log/eb-engine.log`
- Web Server: `/var/log/nginx/access.log`
- PHP: `/var/log/php-fpm/www-error.log`

## Contact Information

**For AWS Infrastructure Issues:**
- Email: aws-team@company.com
- Slack: #aws-support

**For Application Issues:**
- Email: dev-team@company.com
- Slack: #ppdb-support

---

**Last Updated**: $(date)
**Version**: 1.0.0