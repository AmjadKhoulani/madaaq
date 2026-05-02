const express = require('express');
const cors = require('cors');
const { open } = require('sqlite');
const sqlite3 = require('sqlite3');
const path = require('path');
const MikroTikManager = require('./mikrotik');

const app = express();
app.use(cors());
app.use(express.json());

let db;
let mt; // MikroTik Instance

// Initialize Database
async function initDB() {
  db = await open({
    filename: path.join(__dirname, 'madaaq.db'),
    driver: sqlite3.Database
  });

  // Create Tables if not exist
  await db.exec(`
    CREATE TABLE IF NOT EXISTS clients (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      name TEXT,
      phone TEXT,
      address TEXT,
      lat TEXT,
      lng TEXT,
      connType TEXT,
      linkedTower TEXT,
      bbUser TEXT,
      bbPass TEXT,
      portalUser TEXT,
      portalPass TEXT,
      package TEXT,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS towers (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      name TEXT,
      type TEXT,
      location TEXT,
      powerSystem TEXT,
      status TEXT DEFAULT 'online'
    );
  `);
  
  console.log('Database initialized successfully 🚀');
}

// API Routes
app.get('/api/clients', async (req, res) => {
  const clients = await db.all('SELECT * FROM clients ORDER BY id DESC');
  res.json(clients);
});

app.post('/api/clients', async (req, res) => {
  const { name, phone, address, lat, lng, connType, linkedTower, bbUser, bbPass, portalUser, portalPass, package } = req.body;
  const result = await db.run(
    `INSERT INTO clients (name, phone, address, lat, lng, connType, linkedTower, bbUser, bbPass, portalUser, portalPass, package) 
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
    [name, phone, address, lat, lng, connType, linkedTower, bbUser, bbPass, portalUser, portalPass, package]
  );
  res.json({ id: result.lastID, success: true });
});

// MikroTik API Routes
app.post('/api/mikrotik/connect', async (req, res) => {
  const { host, user, pass } = req.body;
  mt = new MikroTikManager(host, user, pass);
  res.json({ success: true, message: 'Configured' });
});

app.get('/api/mikrotik/active', async (req, res) => {
  if (!mt) return res.status(400).json({ error: 'MT not configured' });
  const active = await mt.getActiveSessions();
  res.json(active);
});

// إضافة مشترك دائم (Broadband)
app.post('/api/mikrotik/add-pppoe', async (req, res) => {
  const { username, password, profile } = req.body;
  const result = await mt.addPPPoEUser(username, password, profile);
  res.json(result);
});

// إضافة كرت هوتسبوت (Voucher)
app.post('/api/mikrotik/add-voucher', async (req, res) => {
  const { code, profile } = req.body;
  const result = await mt.addHotspotVoucher(code, profile);
  res.json(result);
});

const PORT = 3000;
initDB().then(() => {
  app.listen(PORT, () => {
    console.log(`MadaaQ Server running on http://localhost:${PORT}`);
  });
});
