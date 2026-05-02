const { Client } = require('ssh2');

const conn = new Client();
const commands = [
  'chown -R madaaq:madaaq /home/madaaq/public_html',
  'chmod -R 755 /home/madaaq/public_html',
  'chmod 644 /home/madaaq/public_html/.htaccess',
  'chmod 644 /home/madaaq/public_html/index.html',
  'ls -la /home/madaaq/public_html/'
];

let cmdIndex = 0;

function runNext() {
  if (cmdIndex >= commands.length) {
    console.log('\n✅ Permissions fixed! Site should work now.');
    conn.end();
    return;
  }
  const cmd = commands[cmdIndex];
  console.log(`\n>>> [${cmdIndex + 1}/${commands.length}] ${cmd}`);
  conn.exec(cmd, (err, stream) => {
    if (err) { console.error('Error:', err); conn.end(); return; }
    stream.on('data', (data) => { process.stdout.write(data); });
    stream.stderr.on('data', (data) => { process.stderr.write(data); });
    stream.on('close', () => { cmdIndex++; runNext(); });
  });
}

conn.on('ready', () => { console.log('🔗 Connected!'); runNext(); });
conn.on('error', (err) => { console.error('Error:', err.message); });

conn.connect({
  host: '173.249.52.218',
  port: 22,
  username: 'root',
  password: '@Amjad2025-#O'
});
