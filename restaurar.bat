@echo off
:: ============================================
:: SCRIPT DE RESTAURACION - PROYECTO WEB
:: ============================================

:: CONFIGURACIÓN
set DB_NAME=proyecto_db
set DB_USER=root
set DB_PASS=
set PROYECTO_PATH=C:\xampp\htdocs\mi_proyecto
set BACKUP_PATH=C:\scripts\backups

color 0E

echo ==========================================
echo       SCRIPT DE RESTAURACION
echo ==========================================
echo.
echo ATENCION: Esto borrara:
echo   - La base de datos actual
echo   - Los archivos del proyecto actual
echo.
echo Luego los restaurara desde los backups.
echo.
echo Asegurate de tener XAMPP (Apache y MySQL) corriendo.
echo.
pause

cls

:: Mostrar backups disponibles
echo ==========================================
echo    BACKUPS DISPONIBLES
echo ==========================================
echo.
echo --- BASE DE DATOS (.sql) ---
dir "%BACKUP_PATH%\*.sql" /b /o-d
echo.
echo --- PROYECTO (.zip) ---
dir "%BACKUP_PATH%\*.zip" /b /o-d
echo.
echo ==========================================
echo.

set /p DB_FILE="Nombre del archivo .sql a restaurar: "
set /p PROYECTO_FILE="Nombre del archivo .zip a restaurar: "

if not exist "%BACKUP_PATH%\%DB_FILE%" (
    echo ERROR: El archivo %DB_FILE% no existe.
    pause
    exit /b 1
)

if not exist "%BACKUP_PATH%\%PROYECTO_FILE%" (
    echo ERROR: El archivo %PROYECTO_FILE% no existe.
    pause
    exit /b 1
)

echo.
echo ==========================================
echo   INICIANDO RESTAURACION...
echo ==========================================

:: 1. RESTAURAR BASE DE DATOS
echo.
echo [1/2] Restaurando base de datos...
echo Eliminando base de datos actual...
"C:\xampp\mysql\bin\mysql.exe" -u %DB_USER% -e "DROP DATABASE IF EXISTS %DB_NAME%;"

echo Creando base de datos nueva...
"C:\xampp\mysql\bin\mysql.exe" -u %DB_USER% -e "CREATE DATABASE %DB_NAME%;"

echo Importando datos desde backup...
"C:\xampp\mysql\bin\mysql.exe" -u %DB_USER% %DB_NAME% < "%BACKUP_PATH%\%DB_FILE%"

if %errorlevel% equ 0 (
    echo OK: Base de datos restaurada correctamente.
) else (
    echo ERROR: Fallo en restauracion de la base de datos.
    pause
    exit /b 1
)

:: 2. RESTAURAR PROYECTO
echo.
echo [2/2] Restaurando archivos del proyecto...
echo Eliminando proyecto actual...
rmdir /s /q "%PROYECTO_PATH%" 2>nul

echo Creando carpeta del proyecto...
mkdir "%PROYECTO_PATH%"

echo Extrayendo archivos desde backup...
powershell -command "Expand-Archive -Path '%BACKUP_PATH%\%PROYECTO_FILE%' -DestinationPath 'C:\xampp\htdocs\' -Force"

if %errorlevel% equ 0 (
    echo OK: Proyecto restaurado correctamente.
) else (
    echo ERROR: Fallo en restauracion del proyecto.
    pause
    exit /b 1
)

:: FINAL
echo.
echo ==========================================
echo   RESTAURACION COMPLETADA CON EXITO
echo ==========================================
echo.
echo Verifica tu web en: http://localhost/mi_proyecto/
echo.
pause