param (
    [string]$Message = "Auto-deploy"
)

Write-Host "--- Starting Local Git Sync ---" -ForegroundColor Cyan
git add .
git commit -m $Message
git push origin master

Write-Host "--- Triggering Server Deployment ---" -ForegroundColor Cyan
plink -batch -pw "@Amjad2025-#O" root@173.249.52.218 "/home/madaaq/public_html/deploy.sh"

Write-Host "--- Deployment Complete! ---" -ForegroundColor Green
