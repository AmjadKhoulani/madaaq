const { Client } = require('ssh2');
const conn = new Client();

conn.on('ready', () => {
  console.log('🔗 Connected for Full Reset and API Start');
  const setupCmd = 'cd /home/madaaq/public_html && git fetch --all && git reset --hard origin/master && cd server && npm install express cors mysql2 node-routeros sqlite sqlite3 && pm2 delete madaaq-api || true && pm2 start index.cjs --name madaaq-api';
  
  conn.exec(setupCmd, (err, stream) => {
    if (err) throw err;
    stream.on('data', (data) => process.stdout.write(data));
    stream.stderr.on('data', (data) => process.stderr.write(data));
    stream.on('close', () => {
      console.log('\n🚀 API restarted with fresh code!');
      conn.end();
    });
  });
}).connect({
  host: '173.249.52.218',
  port: 22,
  username: 'root',
  password: '@Amjad2025-#O'
});
