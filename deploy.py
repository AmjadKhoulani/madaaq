import paramiko
import time

host = "173.249.52.218"
user = "root"
password = "@Amjad2025-#O"

def run_commands():
    try:
        print(f"Connecting to {host}...")
        client = paramiko.SSHClient()
        client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
        client.connect(hostname=host, username=user, password=password)
        
        commands = [
            "cd /home/madaaq/public_html && rm -rf * .git .htaccess",
            "cd /home/madaaq/public_html && git clone https://github.com/AmjadKhoulani/madaaq.git .",
            "cd /home/madaaq/public_html && npm install",
            "cd /home/madaaq/public_html && npm run build",
            "cd /home/madaaq/public_html && chown -R root:root .",
            "cd /home/madaaq/public_html && chmod -R 755 ."
        ]
        
        for cmd in commands:
            print(f"Executing: {cmd}")
            stdin, stdout, stderr = client.exec_command(cmd)
            print(stdout.read().decode())
            print(stderr.read().decode())
            
        client.close()
        print("Success! Site should be live now.")
        
    except Exception as e:
        print(f"Error: {e}")

if __name__ == "__main__":
    run_commands()
