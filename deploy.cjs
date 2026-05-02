const { Client } = require('ssh2');

const conn = new Client();
const commands = [
  'cd /home/madaaq/public_html && git fetch --all',
  'cd /home/madaaq/public_html && git reset --hard origin/master',
  'cd /home/madaaq/public_html && npm install --unsafe-perm',
  'cd /home/madaaq/public_html && chmod -R 755 node_modules',
  'cd /home/madaaq/public_html && npm run build',
  // Clean CP folder
  'cd /home/madaaq/public_html && rm -rf cp && mkdir cp',
  // Move build to CP
  'cd /home/madaaq/public_html && cp -r dist/* cp/',
  // Set the Landing Page (Static) in Root
  'cd /home/madaaq/public_html && mv landing.html index.html',
  // Clean up
  'cd /home/madaaq/public_html && rm -rf dist',
  'cd /home/madaaq/public_html && ls -la'
];

let cmdIndex = 0;

function runNext() {
  if (cmdIndex >= commands.length) {
    console.log('\n✅ ALL DONE! Site should be live on madaaq.com');
    conn.end();
    return;
  }
  const cmd = commands[cmdIndex];
  console.log(`\n>>> [${cmdIndex + 1}/${commands.length}] ${cmd}`);
  conn.exec(cmd, (err, stream) => {
    if (err) { console.error('Error:', err); conn.end(); return; }
    let output = '';
    stream.on('data', (data) => { output += data.toString(); process.stdout.write(data); });
    stream.stderr.on('data', (data) => { process.stderr.write(data); });
    stream.on('close', () => {
      cmdIndex++;
      runNext();
    });
  });
}

conn.on('ready', () => {
  console.log('🔗 Connected to server successfully!');
  runNext();
});

conn.on('error', (err) => {
  console.error('Connection error:', err.message);
});

conn.connect({
  host: '173.249.52.218',
  port: 22,
  username: 'root',
  password: '@Amjad2025-#O'
});
