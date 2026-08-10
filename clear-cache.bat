@echo off
echo ================================
echo  CLEARING LARAVEL CACHE
echo ================================
echo.

echo [1/5] Clearing application cache...
php artisan cache:clear

echo [2/5] Clearing route cache...
php artisan route:clear

echo [3/5] Clearing config cache...
php artisan config:clear

echo [4/5] Clearing view cache...
php artisan view:clear

echo [5/5] Clearing compiled files...
php artisan clear-compiled

echo.
echo ================================
echo  CACHE CLEARED SUCCESSFULLY!
echo ================================
echo.
echo Now refresh your browser with Ctrl+F5
echo.
pause
