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
        type VARCHAR(100) DEFAULT 'tower',
        location TEXT,
        powerSystem TEXT,
        lat VARCHAR(50),
        lng VARCHAR(50),
        notes TEXT,
        status VARCHAR(50) DEFAULT 'online'
      )
    `);
    
    await db.execute(`
      CREATE TABLE IF NOT EXISTS staff (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255),
        phone VARCHAR(50),
        role VARCHAR(50) DEFAULT 'technician',
        area VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
      )
    `);

    await db.execute(`
      CREATE TABLE IF NOT EXISTS settings (
        id INT PRIMARY KEY DEFAULT 1,
        brandName VARCHAR(255),
        currency VARCHAR(10) DEFAULT 'USD',
        supportPhone VARCHAR(50),
        stripePublicKey TEXT,
        stripeSecretKey TEXT,
        whatsappToken TEXT,
        whatsappBusinessId VARCHAR(100),
        whatsappPhoneId VARCHAR(100),
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
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

app.get('/api/towers/:id', async (req, res) => {
  try {
    const [rows] = await db.execute('SELECT * FROM towers WHERE id = ?', [req.params.id]);
    if (rows.length === 0) return res.status(404).json({ error: 'Tower not found' });
    res.json(rows[0]);
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

app.put('/api/towers/:id', async (req, res) => {
  try {
    const { name, type, location, powerSystem, status, notes } = req.body;
    await db.execute(
      `UPDATE towers SET name=?, type=?, location=?, powerSystem=?, status=?, notes=? WHERE id=?`,
      [name, type, location, powerSystem, status || 'online', notes, req.params.id]
    );
    res.json({ success: true });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

app.delete('/api/towers/:id', async (req, res) => {
  try {
    await db.execute('DELETE FROM towers WHERE id = ?', [req.params.id]);
    res.json({ success: true });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

// ─── Clients by ID ────────────────────────────────
app.get('/api/clients/:id', async (req, res) => {
  try {
    const [rows] = await db.execute('SELECT * FROM clients WHERE id = ?', [req.params.id]);
    if (rows.length === 0) return res.status(404).json({ error: 'Client not found' });
    res.json(rows[0]);
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

app.put('/api/clients/:id', async (req, res) => {
  try {
    const { name, phone, address, lat, lng, connType, linkedTower, bbUser, bbPass, portalUser, portalPass, package: packagePlan } = req.body;
    await db.execute(
      `UPDATE clients SET name=?, phone=?, address=?, lat=?, lng=?, connType=?, linkedTower=?, bbUser=?, bbPass=?, portalUser=?, portalPass=?, package=? WHERE id=?`,
      [name, phone, address, lat, lng, connType, linkedTower, bbUser, bbPass, portalUser, portalPass, packagePlan, req.params.id]
    );
    res.json({ success: true });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

app.delete('/api/clients/:id', async (req, res) => {
  try {
    await db.execute('DELETE FROM clients WHERE id = ?', [req.params.id]);
    res.json({ success: true });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});
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

// ─── Staff Routes ───────────────────────────────────
app.get('/api/staff', async (req, res) => {
  try {
    const [rows] = await db.execute('SELECT * FROM staff ORDER BY id DESC');
    res.json(rows);
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

app.post('/api/staff', async (req, res) => {
  try {
    const { name, phone, role, area } = req.body;
    const [result] = await db.execute(
      `INSERT INTO staff (name, phone, role, area) VALUES (?, ?, ?, ?)`,
      [name, phone, role, area]
    );
    res.json({ id: result.insertId, success: true });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

app.put('/api/staff/:id', async (req, res) => {
  try {
    const { name, phone, role, area } = req.body;
    await db.execute(
      `UPDATE staff SET name=?, phone=?, role=?, area=? WHERE id=?`,
      [name, phone, role, area, req.params.id]
    );
    res.json({ success: true });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

app.delete('/api/staff/:id', async (req, res) => {
  try {
    await db.execute('DELETE FROM staff WHERE id = ?', [req.params.id]);
    res.json({ success: true });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

// ─── Settings Routes ────────────────────────────────
app.get('/api/settings', async (req, res) => {
  try {
    const [rows] = await db.execute('SELECT * FROM settings WHERE id = 1');
    res.json(rows[0] || {});
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

app.post('/api/settings', async (req, res) => {
  try {
    const s = req.body;
    await db.execute(
      `INSERT INTO settings (id, brandName, currency, supportPhone, stripePublicKey, stripeSecretKey, whatsappToken, whatsappBusinessId, whatsappPhoneId) 
       VALUES (1, ?, ?, ?, ?, ?, ?, ?, ?)
       ON DUPLICATE KEY UPDATE 
       brandName=?, currency=?, supportPhone=?, stripePublicKey=?, stripeSecretKey=?, whatsappToken=?, whatsappBusinessId=?, whatsappPhoneId=?`,
      [s.brandName, s.currency, s.supportPhone, s.stripePublicKey, s.stripeSecretKey, s.whatsappToken, s.whatsappBusinessId, s.whatsappPhoneId,
       s.brandName, s.currency, s.supportPhone, s.stripePublicKey, s.stripeSecretKey, s.whatsappToken, s.whatsappBusinessId, s.whatsappPhoneId]
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
