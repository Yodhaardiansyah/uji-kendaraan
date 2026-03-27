import PyInstaller.__main__

print("Mulai mem-build Aplikasi RFID Dishub...")

PyInstaller.__main__.run([
    'app.py',                      # Nama file utama Anda
    '--onefile',                       # Jadikan 1 file .exe
    '--noconsole',                     # Hilangkan layar hitam terminal
    '--name=Smart_RFID_Dishub',        # Nama file output .exe
    '--add-data=logo.ico;.',           # Tambahkan logo.ico ke file output
    '--icon=logo.ico',                 # Ikon aplikasi
    '--clean'                          # Bersihkan cache sisa build sebelumnya
])

print("Build Selesai! Cek folder 'dist'.")