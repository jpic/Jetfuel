#!/usr/bin/env python
# 
# create_model.py
# $Id$
# 
# Runs the necessary scripts to generate models
# for a JetFuel application.
# 
# It takes one argument, the path to the JetFuel
# application.

import sys, os

def main():
	# Get the JetFuel directory from the arguments
	args = sys.argv[1:]
	if not len(args) == 1:
		print "usage: create_models.py /path/to/jetfuel"
		sys.exit(-1)
	jetfueldir = args[0]
	
	# Save where we're at
	cwd = os.getcwd()
	
	# Go to the JetFuel directory
	os.chdir(jetfueldir)
	
	# Run the two scripts needed to create a model from a database
	os.system("php core/scripts/createschema.php")
	os.system("echo;")
	os.system("php ezc/trunk/PersistentObjectDatabaseSchemaTiein/src/rungenerator.php -s saved-schema.xml -f xml --overwrite app/model/definitions")
	os.system("echo;")
	
	# Go back from whence you came!
	os.chdir(cwd)

if __name__ == '__main__':
	main()

