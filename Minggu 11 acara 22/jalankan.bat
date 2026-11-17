@echo off
title API Acara 20 - http://localhost:8080
cd /d "%~dp0public"
where php >nul 2>nul
if %errorlevel%==0 (set PHPEXE=php) else (
  for /d %%D in (C:\laragon\bin\php\php-*) do set PHPEXE=%%D\php.exe
)
echo Server berjalan di http://localhost:8080  (tekan Ctrl+C untuk berhenti)
"%PHPEXE%" -S localhost:8080 index.php
pause
