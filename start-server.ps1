# E-ADMISI LITE - Persistent Server Launcher (PowerShell)
# This script runs php artisan serve as a background job
# and auto-restarts it if it crashes.

$projectPath = "c:\xampp82\htdocs\eadmisi-lite"
$port = 8000
$hostAddr = "0.0.0.0"
$logFile = "$projectPath\storage\logs\server-watchdog.log"

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  E-ADMISI LITE - PERSISTENT SERVER" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Server : http://localhost:$port"
Write-Host "PID    : $pid"
Write-Host "Log    : $logFile"
Write-Host ""
Write-Host "Server will auto-restart on crash."
Write-Host "Close this window to stop the server."
Write-Host ""

# Ensure log directory exists
$logDir = Split-Path $logFile -Parent
if (!(Test-Path $logDir)) { New-Item -ItemType Directory -Path $logDir -Force | Out-Null }

function Start-LaravelServer {
    param([string]$logPath)
    
    $timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
    Add-Content -Path $logPath -Value "[$timestamp] Starting Laravel server on port $port..."
    
    # Use Start-Process with -NoNewWindow to keep it in same console group
    # but redirect output to log file
    $process = Start-Process -FilePath "php" -WorkingDirectory $projectPath -ArgumentList @(
        "artisan", "serve", "--host=$hostAddr", "--port=$port"
    ) -NoNewWindow -PassThru -RedirectStandardOutput "$projectPath\storage\logs\server-output.log" -RedirectStandardError "$projectPath\storage\logs\server-error.log"
    
    Add-Content -Path $logPath -Value "[$timestamp] Server started with PID: $($process.Id)"
    return $process
}

# Main watchdog loop
$restartCount = 0
while ($true) {
    $process = Start-LaravelServer -logPath $logFile
    
    # Wait for the process to exit
    $process.WaitForExit()
    
    $exitCode = $process.ExitCode
    $timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
    $restartCount++
    
    Add-Content -Path $logFile -Value "[$timestamp] Server stopped (exit code: $exitCode). Restart #$restartCount in 3s..."
    
    Start-Sleep -Seconds 3
}