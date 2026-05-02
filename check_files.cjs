const { Client } = require('ssh2');
const conn = new Client();

conn.on('ready', () => {
  console.log('🔗 Connected for File Check');
  conn.exec('ls -la /home/madaaq/public_html', (err, stream) => {
    if (err) throw err;
    stream.on('data', (data) => console.log(data.toString()));
    stream.on('close', () => conn.end());
  });
}).connect({
  host: '173.249.52.218',
  port: 22,
  username: 'root',
  password: '@Amjad2025-#O'
});
