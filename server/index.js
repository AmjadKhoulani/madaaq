const express = require('express');
const cors = require('cors');
const { open } = require('sqlite');
const sqlite3 = require('sqlite3');
const path = require('path');

const app = express();
app.use(cors());
app.use(express.json());

let db;

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

const PORT = 3000;
initDB().then(() => {
  app.listen(PORT, () => {
    console.log(`MadaaQ Server running on http://localhost:${PORT}`);
  });
});
