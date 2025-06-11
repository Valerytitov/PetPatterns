import sys
import subprocess

command = '/usr/bin/valentina -u --basename ' + sys.argv[1] + ' --destination ' + sys.argv[2] + ' --format ' + sys.argv[3] + ' --pageformat ' + sys.argv[4] + ' --mfile ' + sys.argv[5] + ' --pedantic ' + sys.argv[6]

cmdResult = subprocess.Popen(command, shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
cmdResult.communicate()

print(command)
print('done')