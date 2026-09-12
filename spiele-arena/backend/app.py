from flask import Flask, render_template, request, redirect, url_for, session
import sqlite3
import os
from werkzeug.security import generate_password_hash, check_password_hash

app = Flask(__name__)
app.secret_key = os.environ.get('SECRET_KEY', 'dev-secret-key-change-in-production')

# --- DATENBANK SETUP ---
def get_db_connection():
    conn = sqlite3.connect("game_arena.db")
    conn.row_factory = sqlite3.Row
    return conn

def init_db():
    """Initialisiert die Datenbank und erstellt die Nutzertabelle automatisch"""
    conn = get_db_connection()
    conn.execute('''
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE NOT NULL,
            password_hash TEXT NOT NULL,
            score INTEGER DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ''')
    conn.commit()
    conn.close()

# Initialisierung beim Start
init_db()

# --- ROUTEN ---

@app.route("/")
def home():
    if "user_id" in session:
        return redirect(url_for("dashboard"))
    return render_template("index.html")

@app.route("/register", methods=["GET", "POST"])
def register():
    if request.method == "POST":
        username = request.form.get("username")
        password = request.form.get("password")
        confirm_pw = request.form.get("confirm_password")

        if password != confirm_pw:
            return "Fehler: Passwoerter stimmen nicht ueberein!"

        if len(password) < 8:
            return "Fehler: Passwort muss mindestens 8 Zeichen lang sein!"

        # Sicheres Hashing des Passworts
        hashed_pw = generate_password_hash(password)

        conn = get_db_connection()
        try:
            conn.execute("INSERT INTO users (username, password_hash) VALUES (?, ?)",
                         (username, hashed_pw))
            conn.commit()
            return redirect(url_for("home"))
        except sqlite3.IntegrityError:
            return "Fehler: Benutzername existiert bereits!"
        finally:
            conn.close()

    return render_template("register.html")

@app.route("/login", methods=["POST"])
def login():
    username = request.form.get("username")
    password = request.form.get("password")

    conn = get_db_connection()
    user = conn.execute("SELECT * FROM users WHERE username = ?", (username,)).fetchone()
    conn.close()

    # Passwortvergleich mit dem Hash aus der Datenbank
    if user and check_password_hash(user["password_hash"], password):
        session["user_id"] = user["id"]
        session["username"] = user["username"]
        return redirect(url_for("dashboard"))

    return "Fehler: Login ungueltig! Bitte Name und Passwort pruefen."

@app.route("/dashboard")
def dashboard():
    if "user_id" not in session:
        return redirect(url_for("home"))
    return render_template("dashboard.html", username=session["username"])

@app.route("/logout")
def logout():
    session.clear()
    return redirect(url_for("home"))

if __name__ == "__main__":
    app.run(debug=os.environ.get('FLASK_DEBUG', 'False') == 'True')
