$ErrorActionPreference = 'Stop'
Set-StrictMode -Version Latest

$projectRoot = Split-Path -Parent $MyInvocation.MyCommand.Path
Set-Location $projectRoot

function Invoke-CheckedDocker {
    param([Parameter(Mandatory = $true)][string[]]$Arguments)

    & docker @Arguments
    if ($LASTEXITCODE -ne 0) {
        throw "Docker command failed with exit code ${LASTEXITCODE}: docker $($Arguments -join ' ')"
    }
}

if (-not (Get-Command docker -ErrorAction SilentlyContinue)) {
    throw 'Docker CLI was not found. Install Docker Desktop and try again.'
}

if (-not (Test-Path '.\docker-compose.yml')) {
    throw "docker-compose.yml was not found in $projectRoot. Run this script from the project folder."
}

Write-Host 'Stopping containers and removing database volumes...' -ForegroundColor Yellow
Invoke-CheckedDocker @('compose', 'down', '--volumes', '--remove-orphans')

Write-Host 'Removing installation lock...' -ForegroundColor Yellow
if (Test-Path '.\var\installed') {
    Remove-Item '.\var\installed' -Force
}

Write-Host 'Restoring install folder name...' -ForegroundColor Yellow
$installPath = Join-Path $projectRoot 'install'
$installedFolders = @(Get-ChildItem -Path $projectRoot -Directory -Filter 'installed_*' | Sort-Object LastWriteTime -Descending)

if (Test-Path $installPath) {
    Write-Host 'The install folder already exists; leaving it unchanged.' -ForegroundColor Green
} elseif ($installedFolders.Count -ge 1) {
    # Reinstall folders are timestamped/randomized. Use the newest complete
    # folder and leave older folders untouched as a recovery copy.
    $selectedFolder = $installedFolders |
        Where-Object {
            (Test-Path (Join-Path $_.FullName 'index.php')) -and
            (Test-Path (Join-Path $_.FullName 'data\constant_format.tpl')) -and
            (Test-Path (Join-Path $_.FullName 'templates\config.tpl'))
        } |
        Select-Object -First 1
    if (-not $selectedFolder) {
        throw 'No complete installed_* folder was found. Restore the installer files before running reset.ps1.'
    }
    Rename-Item -LiteralPath $selectedFolder.FullName -NewName 'install'
    Write-Host "Renamed newest installer $($selectedFolder.Name) to install" -ForegroundColor Green
}

if (-not (Test-Path '.\install\index.php')) {
    throw 'The install folder is incomplete: install/index.php is missing.'
}

Write-Host 'Starting containers...' -ForegroundColor Yellow
Invoke-CheckedDocker @('compose', 'up', '-d', '--build')

Write-Host 'Reset environment is ready. Open http://localhost:8080' -ForegroundColor Green