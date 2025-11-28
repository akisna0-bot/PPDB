@echo off
cd /d "c:\xampp\htdocs\ppdb"

echo Initializing git repository...
git init

echo Adding remote repository...
git remote add origin https://github.com/akisna0-bot/PPDB.git

echo Adding all files...
git add .

echo Committing files...
git commit -m "Initial commit - PPDB Laravel Project"

echo Pushing to GitHub...
git push -u origin main

echo Upload complete!
pause