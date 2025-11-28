# PPDB AWS Infrastructure
terraform {
  required_version = ">= 1.0"
  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "~> 5.0"
    }
  }
}

provider "aws" {
  region = var.aws_region
}

variable "aws_region" {
  description = "AWS region"
  type        = string
  default     = "ap-southeast-1"
}

variable "environment" {
  description = "Environment name"
  type        = string
  default     = "production"
}

variable "app_name" {
  description = "Application name"
  type        = string
  default     = "ppdb"
}

# VPC
resource "aws_vpc" "main" {
  cidr_block           = "10.0.0.0/16"
  enable_dns_hostnames = true
  enable_dns_support   = true

  tags = {
    Name        = "${var.app_name}-vpc"
    Environment = var.environment
  }
}

# RDS Instance
resource "aws_db_instance" "main" {
  identifier     = "${var.app_name}-db"
  engine         = "mysql"
  engine_version = "8.0"
  instance_class = "db.t3.micro"
  
  allocated_storage = 20
  storage_type      = "gp2"
  storage_encrypted = true

  db_name  = "ppdb"
  username = "admin"
  password = "ChangeMePlease123!"

  backup_retention_period = 7
  skip_final_snapshot     = true
  deletion_protection     = false

  tags = {
    Name        = "${var.app_name}-db"
    Environment = var.environment
  }
}

# S3 Bucket
resource "aws_s3_bucket" "uploads" {
  bucket = "${var.app_name}-uploads-${random_string.bucket_suffix.result}"

  tags = {
    Name        = "${var.app_name}-uploads"
    Environment = var.environment
  }
}

resource "random_string" "bucket_suffix" {
  length  = 8
  special = false
  upper   = false
}

output "rds_endpoint" {
  value = aws_db_instance.main.endpoint
}

output "s3_bucket" {
  value = aws_s3_bucket.uploads.id
}