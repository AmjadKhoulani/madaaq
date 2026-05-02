const { Client } = require('ssh2');
const conn = new Client();

conn.on('ready', () => {
  console.log('🔗 Connected for Cleanup');
  const cleanupCmd = 'rm -rf /home/madaaq/public_html/vendor';
  
  conn.exec(cleanupCmd, (err, stream) => {
    if (err) throw err;
    stream.on('data', (data) => process.stdout.write(data));
    stream.on('close', () => {
      console.log('\n✅ Old vendor directory removed successfully');
      conn.end();
    });
  });
}).connect({
  host: '173.249.52.218',
  port: 22,
  username: 'root',
  password: '@Amjad2025-#O'
});
