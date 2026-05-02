const express = require('express');
const cors = require('cors');
const mysql = require('mysql2/promise');
const path = require('path');
const MikroTikManager = require('./mikrotik.cjs');

const app = express();
app.use(cors());
app.use(express.json());

let db;
let mt; // MikroTik Instance

// Initialize MySQL Database
async function initDB() {
  try {
    db = await mysql.createConnection({
      host: 'localhost',
      user: 'madaaq_DataBase',
      password: 't7#AET).mw@)qsaj',
      database: 'madaaq_DataBase'
    });

    // Create Tables if not exist (MySQL Syntax)
    await db.execute(`
      CREATE TABLE IF NOT EXISTS clients (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255),
        phone VARCHAR(50),
        address TEXT,
        lat VARCHAR(50),
        lng VARCHAR(50),
        connType VARCHAR(50),
        linkedTower VARCHAR(100),
        bbUser VARCHAR(100),
        bbPass VARCHAR(100),
        portalUser VARCHAR(100),
        portalPass VARCHAR(100),
        package VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
      )
    `);

    await db.execute(`
      CREATE TABLE IF NOT EXISTS towers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255),
        type VARCHAR(100),
        location TEXT,
        powerSystem TEXT,
        status VARCHAR(50) DEFAULT 'online'
      )
    `);
    
    await db.execute(`
      CREATE TABLE IF NOT EXISTS packages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        type VARCHAR(20) NOT NULL DEFAULT 'broadband',
        name VARCHAR(100) NOT NULL,
        price DECIMAL(10,2) DEFAULT 0,
        download INT DEFAULT 0,
        upload INT DEFAULT 0,
        duration INT DEFAULT 30,
        mikrotik_profile VARCHAR(100),
        notes TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
      )
    `);

    await db.execute(`
      CREATE TABLE IF NOT EXISTS invoices (
        id INT AUTO_INCREMENT PRIMARY KEY,
        client_id INT,
        client_name VARCHAR(255),
        package_name VARCHAR(100),
        amount DECIMAL(10,2),
        status VARCHAR(20) DEFAULT 'pending',
        due_date DATE,
        paid_date DATE,
        notes TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
      )
    `);

    console.log('MySQL Database connected and initialized 🚀');
  } catch (err) {
    console.error('Database connection failed:', err.message);
  }
}

// API Routes
app.get('/api/clients', async (req, res) => {
  try {
    if (!db) throw new Error('Database not initialized');
    const [rows] = await db.execute('SELECT * FROM clients ORDER BY id DESC');
    console.log(`GET /api/clients - Found ${rows.length} records`);
    res.json(rows);
  } catch (err) {
    console.error('API Error (GET /clients):', err.message);
    res.status(500).json({ error: err.message });
  }
});

app.post('/api/clients', async (req, res) => {
  try {
    if (!db) throw new Error('Database not initialized');
    const { name, phone, address, lat, lng, connType, linkedTower, bbUser, bbPass, portalUser, portalPass, package: packagePlan } = req.body;
    console.log(`POST /api/clients - Adding client: ${name}`);
    const [result] = await db.execute(
      `INSERT INTO clients (name, phone, address, lat, lng, connType, linkedTower, bbUser, bbPass, portalUser, portalPass, package) 
       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
      [name, phone, address, lat, lng, connType, linkedTower, bbUser, bbPass, portalUser, portalPass, packagePlan]
    );
    res.json({ id: result.insertId, success: true });
  } catch (err) {
    console.error('API Error (POST /clients):', err.message);
    res.status(500).json({ error: err.message });
  }
});

app.get('/api/towers', async (req, res) => {
  try {
    if (!db) throw new Error('Database not initialized');
    const [rows] = await db.execute('SELECT * FROM towers ORDER BY id DESC');
    res.json(rows);
  } catch (err) {
    console.error('API Error (GET /towers):', err.message);
    res.status(500).json({ error: err.message });
  }
});

app.post('/api/towers', async (req, res) => {
  try {
    if (!db) throw new Error('Database not initialized');
    const { name, type, location, powerSystem } = req.body;
    const [result] = await db.execute(
      `INSERT INTO towers (name, type, location, powerSystem) VALUES (?, ?, ?, ?)`,
      [name, type, location, powerSystem]
    );
    res.json({ id: result.insertId, success: true });
  } catch (err) {
    console.error('API Error (POST /towers):', err.message);
    res.status(500).json({ error: err.message });
  }
});

// ─── Packages Routes ────────────────────────────────
app.get('/api/packages', async (req, res) => {
  try {
    const type = req.query.type || null;
    const query = type
      ? 'SELECT * FROM packages WHERE type = ? ORDER BY price ASC'
      : 'SELECT * FROM packages ORDER BY type, price ASC';
    const params = type ? [type] : [];
    const [rows] = await db.execute(query, params);
    res.json(rows);
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

app.post('/api/packages', async (req, res) => {
  try {
    const { type, name, price, download, upload, duration, mikrotik_profile, notes } = req.body;
    const [result] = await db.execute(
      `INSERT INTO packages (type, name, price, download, upload, duration, mikrotik_profile, notes) VALUES (?,?,?,?,?,?,?,?)`,
      [type || 'broadband', name, price, download, upload, duration || 30, mikrotik_profile, notes]
    );
    res.json({ id: result.insertId, success: true });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

app.put('/api/packages/:id', async (req, res) => {
  try {
    const { name, price, download, upload, duration, mikrotik_profile, notes } = req.body;
    await db.execute(
      `UPDATE packages SET name=?, price=?, download=?, upload=?, duration=?, mikrotik_profile=?, notes=? WHERE id=?`,
      [name, price, download, upload, duration, mikrotik_profile, notes, req.params.id]
    );
    res.json({ success: true });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

app.delete('/api/packages/:id', async (req, res) => {
  try {
    await db.execute('DELETE FROM packages WHERE id = ?', [req.params.id]);
    res.json({ success: true });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

// ─── Invoices Routes ────────────────────────────────
app.get('/api/invoices', async (req, res) => {
  try {
    const [rows] = await db.execute('SELECT * FROM invoices ORDER BY id DESC');
    res.json(rows);
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

app.post('/api/invoices', async (req, res) => {
  try {
    const { client_id, client_name, package_name, amount, status, due_date, notes } = req.body;
    const [result] = await db.execute(
      `INSERT INTO invoices (client_id, client_name, package_name, amount, status, due_date, notes) VALUES (?,?,?,?,?,?,?)`,
      [client_id, client_name, package_name, amount, status || 'pending', due_date, notes]
    );
    res.json({ id: result.insertId, success: true });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

app.put('/api/invoices/:id/pay', async (req, res) => {
  try {
    await db.execute(
      `UPDATE invoices SET status='paid', paid_date=CURDATE() WHERE id=?`,
      [req.params.id]
    );
    res.json({ success: true });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

// ─── MikroTik Routes ────────────────────────────────
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

app.post('/api/mikrotik/add-pppoe', async (req, res) => {
  const { username, password, profile } = req.body;
  const result = await mt.addPPPoEUser(username, password, profile);
  res.json(result);
});

app.post('/api/mikrotik/add-voucher', async (req, res) => {
  const { code, profile } = req.body;
  const result = await mt.addHotspotVoucher(code, profile);
  res.json(result);
});

const PORT = 3000;
initDB().then(() => {
  app.listen(PORT, () => {
    console.log(`MadaaQ Server running on port ${PORT} with MySQL 🚀`);
  });
});
