@echo off
:: ============================================
:: SCRIPT DE BACKUP - PROYECTO WEB
:: ============================================

:: CONFIGURACIÓN
set DB_NAME=proyecto_db
set DB_USER=root
set DB_PASS=
set PROYECTO_PATH=C:\xampp\htdocs\mi_proyecto
set BACKUP_PATH=C:\scripts\backups
set LOG_FILE=C:\scripts\logs\backup_log.txt
set EXTERNAL_PATH=C:\backups_externos

:: Crear fecha y hora para el nombre del archivo
for /f "tokens=1-3 delims=/ " %%a in ('date /t') do (
    set DIA=%%a
    set MES=%%b
    set ANIO=%%c
)
set FECHA=%ANIO%-%MES%-%DIA%_%time:~0,2%-%time:~3,2%-%time:~6,2%
set FECHA=%FECHA: =0%

:: Archivos de salida
set DB_BACKUP=%BACKUP_PATH%\db_%FECHA%.sql
set PROYECTO_BACKUP=%BACKUP_PATH%\proyecto_%FECHA%.zip

:: Crear carpetas si no existen
if not exist "%BACKUP_PATH%" mkdir "%BACKUP_PATH%"
if not exist "C:\scripts\logs" mkdir "C:\scripts\logs"
if not exist "%EXTERNAL_PATH%" mkdir "%EXTERNAL_PATH%"

:: Escribir en el log
echo ========================================== >> %LOG_FILE%
echo Backup iniciado: %date% %time% >> %LOG_FILE%
echo ========================================== >> %LOG_FILE%

:: 1. COPIA DE LA BASE DE DATOS
echo [%time%] Haciendo backup de la base de datos... >> %LOG_FILE%
"C:\xampp\mysql\bin\mysqldump.exe" -u %DB_USER% "%DB_NAME%" > %DB_BACKUP%

if %errorlevel% equ 0 (
    echo [%time%] OK: Base de datos guardada en %DB_BACKUP% >> %LOG_FILE%
) else (
    echo [%time%] ERROR: Fallo en backup de base de datos >> %LOG_FILE%
)

:: 2. COPIA DEL PROYECTO (comprimir)
echo [%time%] Comprimiendo archivos del proyecto... >> %LOG_FILE%
powershell -command "Compress-Archive -Path '%PROYECTO_PATH%' -DestinationPath '%PROYECTO_BACKUP%' -Force"

if %errorlevel% equ 0 (
    echo [%time%] OK: Proyecto comprimido en %PROYECTO_BACKUP% >> %LOG_FILE%
) else (
    echo [%time%] ERROR: Fallo en compresion del proyecto >> %LOG_FILE%
)

:: 3. LIMPIEZA DE BACKUPS ANTIGUOS (más de 7 días)
echo [%time%] Eliminando backups antiguos (mas de 7 dias)... >> %LOG_FILE%
forfiles /p "%BACKUP_PATH%" /m "*.sql" /d -7 /c "cmd /c del @file" 2>nul
forfiles /p "%BACKUP_PATH%" /m "*.zip" /d -7 /c "cmd /c del @file" 2>nul

:: 4. COPIAR BACKUPS A UBICACIÓN EXTERNA (SEGUNDA MEDIDA)
echo [%time%] Copiando backups a ubicacion externa... >> %LOG_FILE%
xcopy "%BACKUP_PATH%\*.sql" "%EXTERNAL_PATH%\" /Y
xcopy "%BACKUP_PATH%\*.zip" "%EXTERNAL_PATH%\" /Y
echo [%time%] OK: Backups copiados a %EXTERNAL_PATH% >> %LOG_FILE%

echo [%time%] Backup finalizado >> %LOG_FILE%
echo. >> %LOG_FILE%

echo Backup completado. Revisa el log en %LOG_FILE%
pause