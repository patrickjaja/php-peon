Description: You can use this, to create your own API as webservices (REST).
				Additional we provide you many features out of the box:
 
 - dynamic webservice
 - High performance
 - smart / simple
 
 Example: Request to custom API including a MySQL SELECT response
 Request: http://localhost/bumpkin/api/server.php?class=test&func=load&testID=1
 Response: `
{
	framework: "php-peon",
	api: "YOUR API",
	version: "1.0",
	status: "OK",
		content: [
		{
			testID: "1",
			name: "test"
		}
	],
	performance: 0.016000986099243
}`

Getting Started: 
 - Clone or Download Repo
 - insert your MySQL Credentials in db.inc.php
 - Create your webservice similar to the class.test.php
 - call your service and enjoy the response
 
 
ToDo (unordered):
 - Parse Paramters
 - Include public/private services via AUTH
 More Documentation: Will be written...trust me ;)