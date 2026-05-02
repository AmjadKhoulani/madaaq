const { Client } = require('ssh2');
const conn = new Client();

conn.on('ready', () => {
  console.log('🔗 Connected for Testing');
  conn.exec('curl -s http://localhost:3000/api/clients', (err, stream) => {
    if (err) throw err;
    stream.on('data', (data) => console.log('RESPONSE:', data.toString()));
    stream.stderr.on('data', (data) => console.error('ERROR:', data.toString()));
    stream.on('close', () => conn.end());
  });
}).connect({
  host: '173.249.52.218',
  port: 22,
  username: 'root',
  password: '@Amjad2025-#O'
});
