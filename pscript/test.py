import subprocess

command = '/usr/bin/valentina --basename ab --destination /var/www/html/pscript --format 1 --pageformat 4 --mfile ~/merki.vit --pedantic ~/comb.val'

cmdResult = subprocess.Popen(command, shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
cmdResult.communicate()

print('test')