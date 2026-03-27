import customtkinter as ctk
import threading
import time
import os
import sys
import json
import pyperclip
from smartcard.System import readers
from smartcard.util import toHexString
from pynput.keyboard import Key, Controller
import ndef

# Coba import pygetwindow untuk fitur Anti-Nyasar
try:
    import pygetwindow as gw
    HAS_GW = True
except ImportError:
    HAS_GW = False

# ==========================================================
# --- FUNGSI HELPER UNTUK BACA FILE BUNDLE PYINSTALLER ---
# ==========================================================
def resource_path(relative_path):
    try:
        base_path = sys._MEIPASS
    except Exception:
        base_path = os.path.abspath(".")
    return os.path.join(base_path, relative_path)
# ==========================================================

APP_NAME = "SMART RFID TOOL"
APP_VERSION = "v1.8.0" # Update Versi (Mini Mode Checkbox & Focus Fix)
COMPANY_NAME = "KEMENTERIAN PERHUBUNGAN"

ctk.set_appearance_mode("Dark") 
ctk.set_default_color_theme("blue")

CONFIG_FILE = "config.json"
keyboard = Controller()

DEFAULT_CONFIG = {
    "BASE_URL": "https://ekirdishub.arunovasi.com/rfid/check/",
    "AUTO_START_MODE": "NONE",
    "LOGO_PATH": "logo.ico",
    "MODE_TYPE": True,        
    "MODE_COPY": False,       
    "MODE_FOCUS": False,      
    "MODE_RAW_READ": False    
}

class AdvancedRFIDApp(ctk.CTk):
    def __init__(self):
        super().__init__()

        self.config = self.load_config()

        self.title(f"{APP_NAME} {APP_VERSION} - {COMPANY_NAME}")
        self.normal_geometry = "700x550"
        self.geometry(self.normal_geometry)
        self.resizable(False, False)

        self.is_mini_mode = False
        self.is_scanning = False
        self.current_mode = None
        
        self.loop_action = "SCAN" 
        self.active_uid = None
        self.last_reader_status = None 

        logo_path = resource_path(self.config["LOGO_PATH"])
        if os.path.exists(logo_path):
            try: self.iconbitmap(logo_path)
            except: pass 

        self.grid_rowconfigure(0, weight=1)
        self.grid_columnconfigure(0, weight=1)

        self.main_menu_frame = ctk.CTkFrame(self, fg_color="transparent")
        self.scan_frame = ctk.CTkFrame(self, fg_color="transparent")
        self.settings_frame = ctk.CTkFrame(self, fg_color="transparent")

        self.build_main_menu()
        self.build_scan_menu()
        self.build_settings_menu()

        if self.config["AUTO_START_MODE"] in ["READ", "WRITE", "DIAGNOSTIC", "FORMAT"]:
            self.start_mode(self.config["AUTO_START_MODE"])
        else:
            self.show_frame(self.main_menu_frame)
            
    # --- MANAJEMEN KONFIGURASI ---
    def load_config(self):
        if not os.path.exists(CONFIG_FILE):
            with open(CONFIG_FILE, "w") as f:
                json.dump(DEFAULT_CONFIG, f, indent=4)
            return DEFAULT_CONFIG.copy()
        try:
            with open(CONFIG_FILE, "r") as f:
                data = json.load(f)
                clean_config = {}
                for key in DEFAULT_CONFIG:
                    clean_config[key] = data.get(key, DEFAULT_CONFIG[key])
                return clean_config
        except Exception:
            return DEFAULT_CONFIG.copy()

    def save_config(self):
        with open(CONFIG_FILE, "w") as f:
            json.dump(self.config, f, indent=4)

    # --- LOGIKA TOGGLE ---
    def on_toggle_type(self):
        if self.cb_auto_type.get() == 1:
            self.cb_auto_copy.deselect()
        self.save_live_settings()

    def on_toggle_copy(self):
        if self.cb_auto_copy.get() == 1:
            self.cb_auto_type.deselect()
        self.save_live_settings()

    def save_live_settings(self):
        self.config["MODE_RAW_READ"] = bool(self.cb_read_raw.get())
        self.config["MODE_TYPE"] = bool(self.cb_auto_type.get())
        self.config["MODE_COPY"] = bool(self.cb_auto_copy.get())
        self.config["MODE_FOCUS"] = bool(self.cb_anti_nyasar.get())
        self.save_config()

    def show_frame(self, frame):
        self.main_menu_frame.grid_forget()
        self.scan_frame.grid_forget()
        self.settings_frame.grid_forget()
        frame.grid(row=0, column=0, sticky="nsew", padx=20, pady=20)

    # --- TAMPILAN 1: MENU UTAMA ---
    def build_main_menu(self):
        header_frame = ctk.CTkFrame(self.main_menu_frame, fg_color="#1E88E5", corner_radius=10)
        header_frame.pack(fill="x", pady=(0, 20), ipady=15)
        
        ctk.CTkLabel(header_frame, text=COMPANY_NAME, font=ctk.CTkFont(size=14, weight="bold"), text_color="#BBDEFB").pack(pady=(10, 0))
        ctk.CTkLabel(header_frame, text=APP_NAME, font=ctk.CTkFont(size=28, weight="bold"), text_color="white").pack(pady=(0, 5))
        ctk.CTkLabel(header_frame, text=f"Versi {APP_VERSION}", font=ctk.CTkFont(size=12), text_color="#90CAF9").pack(pady=(0, 10))

        btn_container = ctk.CTkFrame(self.main_menu_frame, fg_color="transparent")
        btn_container.pack(fill="x", padx=40)
        btn_container.grid_columnconfigure(0, weight=1)
        btn_container.grid_columnconfigure(1, weight=1)

        btn_mode1 = ctk.CTkButton(btn_container, text="📝 Tulis URL ke Kartu", height=45, font=ctk.CTkFont(size=14, weight="bold"), command=lambda: self.start_mode("WRITE"))
        btn_mode1.grid(row=0, column=0, pady=10, padx=10, sticky="ew")

        btn_mode2 = ctk.CTkButton(btn_container, text="💻 Baca & Buka Web", height=45, font=ctk.CTkFont(size=14, weight="bold"), fg_color="#00897B", hover_color="#00695C", command=lambda: self.start_mode("READ"))
        btn_mode2.grid(row=0, column=1, pady=10, padx=10, sticky="ew")

        btn_mode3 = ctk.CTkButton(btn_container, text="🔍 Diagnostik Kartu", height=45, font=ctk.CTkFont(size=14, weight="bold"), fg_color="#F57C00", hover_color="#EF6C00", command=lambda: self.start_mode("DIAGNOSTIC"))
        btn_mode3.grid(row=1, column=0, pady=10, padx=10, sticky="ew")

        btn_mode4 = ctk.CTkButton(btn_container, text="🧹 Format / Timpa Kartu", height=45, font=ctk.CTkFont(size=14, weight="bold"), fg_color="#8E24AA", hover_color="#6A1B9A", command=lambda: self.start_mode("FORMAT"))
        btn_mode4.grid(row=1, column=1, pady=10, padx=10, sticky="ew")

        bottom_frame = ctk.CTkFrame(self.main_menu_frame, fg_color="transparent")
        bottom_frame.pack(fill="x", padx=50, pady=(30, 10))
        
        ctk.CTkButton(bottom_frame, text="⚙️ Pengaturan", height=40, fg_color="#546E7A", hover_color="#37474F", command=lambda: self.show_frame(self.settings_frame)).pack(side="left", expand=True, fill="x", padx=(0, 5))
        ctk.CTkButton(bottom_frame, text="🚪 Keluar", height=40, fg_color="#E53935", hover_color="#C62828", command=self.destroy).pack(side="right", expand=True, fill="x", padx=(5, 0))

    # --- TAMPILAN 2: LAYAR SCANNING ---
    def build_scan_menu(self):
        scan_header = ctk.CTkFrame(self.scan_frame, fg_color="transparent")
        scan_header.pack(fill="x", pady=(10, 0))
        
        self.scan_title = ctk.CTkLabel(scan_header, text="Loading...", font=ctk.CTkFont(size=24, weight="bold"))
        self.scan_title.pack(side="left", padx=20)

        self.btn_mini_mode = ctk.CTkButton(scan_header, text="📌 Mini", width=60, height=30, fg_color="#607D8B", hover_color="#455A64", command=self.toggle_mini_mode)
        self.btn_mini_mode.pack(side="right", padx=20)
        
        self.lbl_reader_status = ctk.CTkLabel(scan_header, text="⚫ Cek Reader...", font=ctk.CTkFont(size=14, weight="bold"))
        self.lbl_reader_status.pack(side="right", padx=10)

        self.status_box = ctk.CTkFrame(self.scan_frame, corner_radius=15, fg_color="#263238")
        self.status_box.pack(pady=(10, 5), fill="both", expand=True, padx=40)

        self.scan_status = ctk.CTkLabel(self.status_box, text="Menyiapkan reader...", font=ctk.CTkFont(size=18), text_color="white", wraplength=400)
        self.scan_status.pack(expand=True, padx=20, pady=20)

        self.dynamic_tools_frame = ctk.CTkFrame(self.scan_frame, fg_color="transparent")
        self.dynamic_tools_frame.pack(fill="x", pady=0)

        self.cb_read_raw = ctk.CTkCheckBox(self.dynamic_tools_frame, text="📂 Baca Isi Asli Kartu (Abaikan Base URL)", font=ctk.CTkFont(weight="bold"), fg_color="#FF9800", command=self.save_live_settings)
        self.cb_auto_type = ctk.CTkCheckBox(self.dynamic_tools_frame, text="⌨️ Auto-Ketik & Enter", font=ctk.CTkFont(weight="bold"), command=self.on_toggle_type)
        self.cb_auto_copy = ctk.CTkCheckBox(self.dynamic_tools_frame, text="📋 Salin Link ke Clipboard (Copy)", font=ctk.CTkFont(weight="bold"), command=self.on_toggle_copy)
        
        state = "normal" if HAS_GW else "disabled"
        txt = "🛡️ Fokuskan Browser (Anti-Nyasar)" if HAS_GW else "🛡️ Anti-Nyasar (Install pygetwindow)"
        self.cb_anti_nyasar = ctk.CTkCheckBox(self.dynamic_tools_frame, text=txt, state=state, font=ctk.CTkFont(weight="bold"), command=self.save_live_settings)

        self.confirm_frame = ctk.CTkFrame(self.scan_frame, fg_color="transparent")
        self.btn_confirm = ctk.CTkButton(self.confirm_frame, text="✅ Lanjutkan Eksekusi", height=45, font=ctk.CTkFont(size=15, weight="bold"), fg_color="#4CAF50", hover_color="#388E3C", command=self.on_confirm_action)
        self.btn_cancel_action = ctk.CTkButton(self.confirm_frame, text="❌ Batal", height=45, font=ctk.CTkFont(size=15, weight="bold"), fg_color="#E53935", hover_color="#C62828", command=self.on_cancel_action)

        self.btn_back = ctk.CTkButton(self.scan_frame, text="⏹️ Hentikan Mode & Kembali", height=45, font=ctk.CTkFont(size=14, weight="bold"), fg_color="gray30", hover_color="gray20", command=self.stop_scanning)
        self.btn_back.pack(pady=(15, 20), padx=100, fill="x")

    def toggle_mini_mode(self):
        self.is_mini_mode = not self.is_mini_mode
        if self.is_mini_mode:
            # MASUK MINI MODE
            self.btn_mini_mode.configure(text="🔳 Max", fg_color="#4CAF50")
            self.attributes('-topmost', True) 
            
            # Khusus Mode Read, beri tinggi ekstra agar Checkbox muat
            if self.current_mode == "READ":
                self.geometry("380x300") 
            else:
                self.geometry("350x180")
            
            self.scan_title.pack_forget()
            self.lbl_reader_status.pack_forget()
            self.btn_back.pack_forget()
            self.confirm_frame.pack_forget() 
            self.status_box.pack(pady=5, fill="both", expand=True, padx=10)
            self.scan_status.configure(font=ctk.CTkFont(size=14))
            
            # Mengecilkan font & merapatkan margin checkbox agar muat
            mini_font = ctk.CTkFont(size=11, weight="bold")
            self.cb_read_raw.configure(font=mini_font)
            self.cb_auto_type.configure(font=mini_font)
            self.cb_auto_copy.configure(font=mini_font)
            self.cb_anti_nyasar.configure(font=mini_font)

            if self.current_mode == "READ":
                self.cb_read_raw.pack(anchor="w", padx=20, pady=1)
                self.cb_auto_type.pack(anchor="w", padx=20, pady=1)
                self.cb_auto_copy.pack(anchor="w", padx=20, pady=1)
                self.cb_anti_nyasar.pack(anchor="w", padx=20, pady=1)

        else:
            # KEMBALI KE NORMAL
            self.btn_mini_mode.configure(text="📌 Mini", fg_color="#607D8B")
            self.attributes('-topmost', False) 
            self.geometry(self.normal_geometry) 
            
            self.scan_title.pack(side="left", padx=20)
            self.lbl_reader_status.pack(side="right", padx=10)
            
            # Kembalikan font ke normal
            normal_font = ctk.CTkFont(size=13, weight="bold")
            self.cb_read_raw.configure(font=normal_font)
            self.cb_auto_type.configure(font=normal_font)
            self.cb_auto_copy.configure(font=normal_font)
            self.cb_anti_nyasar.configure(font=normal_font)

            if self.current_mode == "READ":
                self.dynamic_tools_frame.pack(fill="x", pady=0)
                self.cb_read_raw.pack(anchor="w", padx=100, pady=2)
                self.cb_auto_type.pack(anchor="w", padx=100, pady=2)
                self.cb_auto_copy.pack(anchor="w", padx=100, pady=2)
                self.cb_anti_nyasar.pack(anchor="w", padx=100, pady=2)
            
            self.btn_back.pack(pady=(15, 20), padx=100, fill="x")
            self.status_box.pack(pady=(10, 5), fill="both", expand=True, padx=40)
            self.scan_status.configure(font=ctk.CTkFont(size=18))

    # --- TAMPILAN 3: PENGATURAN ---
    def build_settings_menu(self):
        ctk.CTkLabel(self.settings_frame, text="⚙️ Pengaturan Dasar", font=ctk.CTkFont(size=24, weight="bold")).pack(pady=(20, 20))
        
        ctk.CTkLabel(self.settings_frame, text="Base URL (Untuk Mode UID):").pack(anchor="w", padx=60)
        self.url_entry = ctk.CTkEntry(self.settings_frame, height=40)
        self.url_entry.insert(0, self.config["BASE_URL"])
        self.url_entry.pack(fill="x", padx=60, pady=(0, 25))
        
        ctk.CTkLabel(self.settings_frame, text="Jalankan Otomatis saat dibuka:").pack(anchor="w", padx=60, pady=(10,0))
        self.opt_autostart = ctk.CTkOptionMenu(self.settings_frame, values=["NONE", "READ", "WRITE", "DIAGNOSTIC", "FORMAT"])
        self.opt_autostart.set(self.config["AUTO_START_MODE"])
        self.opt_autostart.pack(fill="x", padx=60, pady=(0, 30))
        
        ctk.CTkButton(self.settings_frame, text="💾 Simpan Pengaturan", height=45, command=self.apply_settings).pack(fill="x", padx=100, pady=10)
        ctk.CTkButton(self.settings_frame, text="🔙 Kembali", height=40, fg_color="#546E7A", command=lambda: self.show_frame(self.main_menu_frame)).pack(fill="x", padx=100, pady=5)

    def apply_settings(self):
        self.config["BASE_URL"] = self.url_entry.get()
        self.config["AUTO_START_MODE"] = self.opt_autostart.get()
        self.save_config()
        self.show_frame(self.main_menu_frame)

    # --- HELPER UI & FOKUS BROWSER ---
    def update_status_ui(self, text, color="white"):
        self.after(0, lambda: self.scan_status.configure(text=text, text_color=color))

    def copy_to_clipboard_safe(self, text):
        self.after(0, lambda: pyperclip.copy(text))

    def force_focus_browser(self):
        """ Trik Bypass Foreground Lock Windows untuk Memfokuskan Browser """
        if not HAS_GW: return
        try:
            # Windows menolak perpindahan aplikasi jika user baru saja mengklik layar lain.
            # Trik: Menekan tombol "ALT" secara virtual akan mereset timer lock tersebut!
            keyboard.press(Key.alt)
            keyboard.release(Key.alt)
            time.sleep(0.1)

            # Cari Browser yang terbuka
            browsers = gw.getWindowsWithTitle('Chrome') + gw.getWindowsWithTitle('Edge') + gw.getWindowsWithTitle('Firefox')
            for b in browsers:
                if b.title.strip() != "":
                    try:
                        if b.isMinimized: b.restore()
                        b.activate()
                        time.sleep(0.3)
                        return # Selesai setelah menemukan 1 browser
                    except:
                        pass # Jika browser ini error, lanjut ke browser berikutnya
        except Exception:
            pass 

    # --- LOGIKA KONFIRMASI ---
    def prompt_user_confirmation(self, uid, mode):
        def update_gui():
            if self.is_mini_mode: self.toggle_mini_mode() 
            if mode == "WRITE":
                self.scan_status.configure(text=f"💳 UID Terdeteksi: {uid}\n\nTetap tempelkan kartu, lalu klik:\n'Lanjutkan Eksekusi' untuk MENULIS.", text_color="#FFCA28")
            else:
                self.scan_status.configure(text=f"⚠️ UID Terdeteksi: {uid}\n\nTetap tempelkan kartu, lalu klik:\n'Lanjutkan Eksekusi' untuk MENIMPA KARTU.", text_color="#EF5350")
            
            self.confirm_frame.pack(pady=(0,20))
            self.btn_confirm.pack(side="left", padx=10, expand=True, fill="x")
            self.btn_cancel_action.pack(side="right", padx=10, expand=True, fill="x")
        self.after(0, update_gui)

    def on_confirm_action(self):
        self.confirm_frame.pack_forget()
        if self.current_mode == "WRITE":
            self.update_status_ui(f"⏳ Jangan lepas kartu, sedang menulis URL...", "#29B6F6")
            self.loop_action = "DO_WRITE"
        elif self.current_mode == "FORMAT":
            self.update_status_ui(f"⏳ Jangan lepas kartu, sedang menimpa dengan UID...", "#FFB300")
            self.loop_action = "DO_FORMAT"

    def on_cancel_action(self):
        self.confirm_frame.pack_forget()
        self.update_status_ui("❌ Dibatalkan.\nAngkat kartu, lalu tempelkan kartu lain.", "gray")
        self.active_uid = None
        self.loop_action = "SCAN"

    # --- LOGIKA MODE ---
    def start_mode(self, mode_type):
        self.is_scanning = True
        self.current_mode = mode_type
        self.loop_action = "SCAN"
        self.active_uid = None
        self.last_reader_status = None 
        
        self.show_frame(self.scan_frame)
        self.btn_back.configure(state="normal")
        self.confirm_frame.pack_forget()
        
        # Load state Checkbox dari memori config
        if self.config.get("MODE_RAW_READ", False): self.cb_read_raw.select()
        else: self.cb_read_raw.deselect()

        if self.config.get("MODE_TYPE", True): self.cb_auto_type.select()
        else: self.cb_auto_type.deselect()
        
        if self.config.get("MODE_COPY", False): self.cb_auto_copy.select()
        else: self.cb_auto_copy.deselect()
        
        if self.config.get("MODE_FOCUS", False) and HAS_GW: self.cb_anti_nyasar.select()
        else: self.cb_anti_nyasar.deselect()

        # Tampilkan Checkbox HANYA di Mode Baca
        if mode_type == "READ":
            self.dynamic_tools_frame.pack(fill="x", pady=0)
            self.cb_read_raw.pack(anchor="w", padx=100, pady=2)
            self.cb_auto_type.pack(anchor="w", padx=100, pady=2)
            self.cb_auto_copy.pack(anchor="w", padx=100, pady=2)
            self.cb_anti_nyasar.pack(anchor="w", padx=100, pady=2)
            self.scan_title.configure(text="💻 BACA & BUKA WEB")
            self.update_status_ui("Tentukan mode di bawah, lalu tempelkan kartu...", "#FFCA28")
        else:
            self.dynamic_tools_frame.pack_forget()
            self.cb_read_raw.pack_forget()
            self.cb_auto_type.pack_forget()
            self.cb_auto_copy.pack_forget()
            self.cb_anti_nyasar.pack_forget()

        if self.is_mini_mode: self.toggle_mini_mode()
        
        if mode_type == "WRITE":
            self.scan_title.configure(text="📝 TULIS KARTU")
            self.update_status_ui("💳 Tempelkan kartu untuk menulis data URL...", "#FFCA28")
        elif mode_type == "DIAGNOSTIC":
            self.scan_title.configure(text="🔍 DIAGNOSTIK KARTU")
            self.update_status_ui("🛠️ Tempelkan kartu untuk mengecek informasi...", "#FFCA28")
        elif mode_type == "FORMAT":
            self.scan_title.configure(text="🧹 FORMAT / TIMPA KARTU")
            self.update_status_ui("⚠️ Tempelkan kartu untuk MENIMPA isi dengan teks UID...", "#EF5350")

        threading.Thread(target=self.rfid_master_loop, daemon=True).start()

    def stop_scanning(self):
        self.is_scanning = False
        self.loop_action = "SCAN"
        self.btn_back.configure(state="disabled")
        self.confirm_frame.pack_forget()
        self.dynamic_tools_frame.pack_forget()
        self.update_status_ui("Menghentikan perangkat...", "gray")
        self.after(1000, lambda: self.show_frame(self.main_menu_frame))

    # --- FUNGSI BACA ISI KARTU ASLI (NDEF) ---
    def read_ndef_from_card(self, conn):
        try:
            data = bytearray()
            for page in range(4, 25): 
                resp, sw1, sw2 = conn.transmit([0xFF, 0xB0, 0x00, page, 0x04])
                if sw1 == 0x90:
                    data.extend(resp)
                else:
                    break
            
            idx = 0
            while idx < len(data) - 1:
                if data[idx] == 0x03: 
                    msg_len = data[idx+1]
                    if msg_len == 0: return ""
                    
                    if idx + 2 + msg_len <= len(data):
                        ndef_data = data[idx+2 : idx+2+msg_len]
                        records = list(ndef.message_decoder(ndef_data))
                        if records:
                            rec = records[0]
                            if isinstance(rec, ndef.UriRecord):
                                return rec.uri
                            elif isinstance(rec, ndef.TextRecord):
                                return rec.text
                    break
                idx += 1
        except Exception:
            pass
        return ""

    # --- FUNGSI EKSEKUSI RFID ---
    def execute_write(self, conn, uid):
        url = self.config["BASE_URL"] + uid
        try:
            records = [ndef.UriRecord(url)]
            payload = b''.join(ndef.message_encoder(records))
            ndef_msg = bytearray([0x03, len(payload)]) + payload + bytearray([0xFE])
            
            idx, page = 0, 4
            while idx < len(ndef_msg):
                chunk = list(ndef_msg[idx:idx+4])
                while len(chunk) < 4: chunk.append(0x00)
                conn.transmit([0xFF, 0xD6, 0x00, page, 0x04] + chunk)
                idx += 4
                page += 1
            self.update_status_ui(f"✅ BERHASIL!\nData URL masuk ke kartu: {uid}", "#66BB6A")
        except:
            self.update_status_ui(f"❌ GAGAL MENULIS.\nPastikan kartu tidak dilepas saat proses.", "#EF5350")

    def execute_format(self, conn, uid):
        try:
            records = [ndef.TextRecord(uid)]
            payload = b''.join(ndef.message_encoder(records))
            ndef_msg = bytearray([0x03, len(payload)]) + payload + bytearray([0xFE])
            
            idx, page = 0, 4
            while idx < len(ndef_msg):
                chunk = list(ndef_msg[idx:idx+4])
                while len(chunk) < 4: chunk.append(0x00)
                conn.transmit([0xFF, 0xD6, 0x00, page, 0x04] + chunk)
                idx += 4
                page += 1
            self.update_status_ui(f"✅ FORMAT BERHASIL!\nKartu {uid} telah ditimpa dengan teks UID.", "#66BB6A")
        except:
            self.update_status_ui(f"❌ GAGAL FORMAT.\nPastikan kartu tidak dilepas saat proses.", "#EF5350")

    def execute_read_diag(self, conn, uid, data):
        do_raw_read = self.cb_read_raw.get() == 1
        raw_text = self.read_ndef_from_card(conn)
        final_output_text = raw_text if do_raw_read else (self.config["BASE_URL"] + uid)
        
        if self.current_mode == "DIAGNOSTIC":
            isi_tampil = raw_text if raw_text else "(Kosong / Tidak ada data NDEF)"
            msg = f"✅ KARTU TERBACA!\n\n🔹 UID:\n{uid}\n\n🔹 Isi Asli Kartu:\n{isi_tampil}\n\nPanjang UID: {len(data)} bytes"
            self.update_status_ui(msg, "#29B6F6")
            
        elif self.current_mode == "READ":
            if do_raw_read and not raw_text:
                self.update_status_ui(f"❌ Kartu Kosong (Tidak ada NDEF/Link).\nFormat kartu atau matikan centang 'Baca Isi Asli'.", "#EF5350")
                return

            do_type = self.cb_auto_type.get() == 1
            do_copy = self.cb_auto_copy.get() == 1
            do_focus = self.cb_anti_nyasar.get() == 1

            msg = f"✅ {('Isi Kartu Asli' if do_raw_read else 'Generate URL')}: Terbaca!\n\n"
            
            if do_copy:
                self.copy_to_clipboard_safe(final_output_text)
                msg += "📋 Teks berhasil disalin ke Clipboard.\n"
                
            if do_type:
                if do_focus:
                    msg += "🔍 Fokus ke browser & mengetik..."
                    self.force_focus_browser()
                else:
                    msg += "⌨️ Mengetik ke aktif window..."
                    
                keyboard.type(final_output_text)
                keyboard.press(Key.enter)
                keyboard.release(Key.enter)
                
            if not do_copy and not do_type:
                msg += "⚠️ Tidak ada aksi. (Centang Ketik / Salin di bawah)"

            self.update_status_ui(msg, "#66BB6A")

    # --- MASTER LOOP ---
    def rfid_master_loop(self):
        last_uid = None
        while self.is_scanning:
            
            r = readers()
            is_reader_connected = len(r) > 0
            
            if is_reader_connected != self.last_reader_status:
                if is_reader_connected:
                    self.after(0, lambda: self.lbl_reader_status.configure(text="🟢 Reader Terhubung", text_color="#4CAF50"))
                else:
                    self.after(0, lambda: self.lbl_reader_status.configure(text="🔴 Reader Terputus", text_color="#EF5350"))
                    if self.loop_action == "WAIT_USER":
                        self.on_cancel_action()
                self.last_reader_status = is_reader_connected

            if not is_reader_connected:
                time.sleep(0.5)
                continue

            if self.loop_action == "WAIT_USER":
                time.sleep(0.1)
                continue

            try:
                reader = r[0]
                conn = reader.createConnection()
                conn.connect()
                data, _, _ = conn.transmit([0xFF, 0xCA, 0x00, 0x00, 0x00])
                uid = toHexString(data).replace(" ", "").upper()

                if self.loop_action == "DO_WRITE":
                    if uid == self.active_uid: self.execute_write(conn, uid)
                    else: self.update_status_ui("❌ Gagal: Kartu berbeda dari konfirmasi!", "#EF5350")
                    self.loop_action = "SCAN"
                    last_uid = uid
                    time.sleep(1.5)
                    if self.is_scanning: self.update_status_ui("💳 Selesai! Tempelkan kartu selanjutnya...", "#FFCA28")
                    continue

                if self.loop_action == "DO_FORMAT":
                    if uid == self.active_uid: self.execute_format(conn, uid)
                    else: self.update_status_ui("❌ Gagal: Kartu berbeda dari konfirmasi!", "#EF5350")
                    self.loop_action = "SCAN"
                    last_uid = uid
                    time.sleep(1.5)
                    if self.is_scanning: self.update_status_ui("💳 Selesai! Tempelkan kartu selanjutnya...", "#FFCA28")
                    continue

                if uid != last_uid:
                    if self.current_mode in ["WRITE", "FORMAT"]:
                        self.active_uid = uid
                        self.loop_action = "WAIT_USER"
                        self.prompt_user_confirmation(uid, self.current_mode)
                    else:
                        self.execute_read_diag(conn, uid, data)
                        last_uid = uid
                        time.sleep(1.5)
                        if self.is_scanning: self.update_status_ui("💳 Siap! Tempelkan kartu selanjutnya...", "#FFCA28")

            except Exception as e:
                if self.loop_action in ["DO_WRITE", "DO_FORMAT"]:
                    self.update_status_ui("❌ Gagal: Koneksi terputus. Jangan lepas kartu saat proses!", "#EF5350")
                    self.loop_action = "SCAN"
                    time.sleep(1.5)
                    if self.is_scanning: self.update_status_ui("💳 Siap! Tempelkan kartu selanjutnya...", "#FFCA28")
                last_uid = None

            time.sleep(0.3)

if __name__ == "__main__":
    app = AdvancedRFIDApp()
    app.mainloop()