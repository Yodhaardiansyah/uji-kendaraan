@echo off
echo Memulai proses build aplikasi RFID Dishub...
pyinstaller --noconsole --add-data "logo.ico" --onefile --name "Smart_RFID_Dishub" --icon="logo.ico" app.py
echo.
echo Proses selesai! Silakan cek folder "dist".
pause