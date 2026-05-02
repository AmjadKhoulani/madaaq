const express = require('express');
const cors = require('cors');
const mysql = require('mysql2/promise');
const path = require('path');
const MikroTikManager = require('./mikrotik');

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
    
    console.log('MySQL Database connected and initialized 🚀');
  } catch (err) {
    console.error('Database connection failed:', err.message);
  }
}

// API Routes
app.get('/api/clients', async (req, res) => {
  const [rows] = await db.execute('SELECT * FROM clients ORDER BY id DESC');
  res.json(rows);
});

app.post('/api/clients', async (req, res) => {
  const { name, phone, address, lat, lng, connType, linkedTower, bbUser, bbPass, portalUser, portalPass, package } = req.body;
  const [result] = await db.execute(
    `INSERT INTO clients (name, phone, address, lat, lng, connType, linkedTower, bbUser, bbPass, portalUser, portalPass, package) 
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
    [name, phone, address, lat, lng, connType, linkedTower, bbUser, bbPass, portalUser, portalPass, package]
  );
  res.json({ id: result.insertId, success: true });
});

app.get('/api/towers', async (req, res) => {
  const [rows] = await db.execute('SELECT * FROM towers ORDER BY id DESC');
  res.json(rows);
});

app.post('/api/towers', async (req, res) => {
  const { name, type, location, powerSystem } = req.body;
  const [result] = await db.execute(
    `INSERT INTO towers (name, type, location, powerSystem) VALUES (?, ?, ?, ?)`,
    [name, type, location, powerSystem]
  );
  res.json({ id: result.insertId, success: true });
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
