const express = require('express');
const app = express();
const bodyParser = require('body-parser');

app.use(express.json());

app.post('/make', (request, response) => {
	
	const { spawn } = require('child_process');
	
	const runPython = '/var/www/html/pscript/args.py';	
	const args = [request.query.name, request.query.destination, request.query.format, request.query.page, request.query.vit, request.query.val];
	args.unshift(runPython);
	
	const resultPython = spawn('python3', args);
	
	resultPython.stdout.on('data', (data) => {
		
		console.log(data);
		res.status(200).send();
		
	});

	resultPython.stderr.on('data', (data) => {
		
		console.log('error: ' + data);
		
	});

	resultPython.on('close', (code) => {
		
		console.log('exited');
		
	});
	
	return false;
	
});

app.listen(37085);