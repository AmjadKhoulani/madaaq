const { Client } = require('ssh2');
const conn = new Client();

conn.on('ready', () => {
  console.log('🔗 Connected for API Setup');
  conn.exec('npm install -g pm2 && cd /home/madaaq/public_html/server && npm install && pm2 delete madaaq-api || true && pm2 start index.js --name madaaq-api', (err, stream) => {
    if (err) throw err;
    stream.on('data', (data) => process.stdout.write(data));
    stream.stderr.on('data', (data) => process.stderr.write(data));
    stream.on('close', () => {
      console.log('\n🚀 API is now running in background via PM2');
      conn.end();
    });
  });
}).connect({
  host: '173.249.52.218',
  port: 22,
  username: 'root',
  password: '@Amjad2025-#O'
});
