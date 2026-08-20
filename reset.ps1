Write-Host "Stopping containers and removing volumes..." -ForegroundColor Yellow
docker compose down -v

Write-Host "Removing var/installed marker..." -ForegroundColor Yellow
if (Test-Path .\var\installed) {
    Remove-Item .\var\installed -Force
}

Write-Host "Restoring install folder name if needed..." -ForegroundColor Yellow
$installedFolder = Get-ChildItem . -Directory | Where-Object { $_.Name -like "installed_*" } | Select-Object -First 1
if ($installedFolder -and -not (Test-Path .\install)) {
    Rename-Item $installedFolder.FullName "install"
    Write-Host "Renamed $($installedFolder.Name) back to install" -ForegroundColor Green
}

Write-Host "Starting containers..." -ForegroundColor Yellow
docker compose up -d

Write-Host "Done. Open http://localhost:8080" -ForegroundColor Green