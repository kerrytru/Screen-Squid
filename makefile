
#Build date Tuesday 21th of July 2020 10:59:28 AM
#Build revision 1.1

#Author: Rid-lin (https://github.com/Rid-lin)

# DIR - The name of the folder in which ScreenSquid will be installed
DIR = screensquid
# PREFIX - The path where ScreenSquid will be installed
PREFIX = /var/www/html

# The user and group that is required to start the http server
WWW-USER = apache
WWW-GROUP = apache

.PHONY :install

install: sayhello install-copy fix-permission sayOK

sayhello:
	@echo ''
	@echo 'Hello! Starting install Screen Squid'
	@echo 'If you have any questions about Screen Squid, contact us http://t.me/screensquid'
	@echo ''

sayOK:
	@echo ''
	@echo 'Screen Squid installed successfully! Please, go to browser and type http://yourserverip/screensquid'
	
install-copy:
	mkdir -p $(PREFIX)/$(DIR)
	cp -r . $(PREFIX)/$(DIR)

fix-permission:
	chown $(WWW-USER):$(WWW-GROUP) -R $(PREFIX)/$(DIR)
	chmod 775 -R $(PREFIX)/$(DIR)/modules/Chart/pictures
	chmod +x -R $(PREFIX)/$(DIR)/*.pl