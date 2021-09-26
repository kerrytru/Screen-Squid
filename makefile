#<!#CR>
#************************************************************************************************************************
#*                                                    Copyrigths ©                                                      *
#* -------------------------------------------------------------------------------------------------------------------- *
#* -------------------------------------------------------------------------------------------------------------------- *
#*                                           File and License Informations                                              *
#* -------------------------------------------------------------------------------------------------------------------- *
#*                         File Name    > <!#FN> makefile </#FN>                                                        
#*                         File Birth   > <!#FB> 2021/09/11 17:04:26.522 </#FB>                                         *
#*                         File Mod     > <!#FT> 2021/09/26 20:24:02.921 </#FT>                                         *
#*                         License      > <!#LT> ERROR: no License name provided! </#LT>                                
#*                                        <!#LU>  </#LU>                                                                
#*                                        <!#LD> MIT License                                                            
#*                                        GNU General Public License version 3.0 (GPLv3) </#LD>                         
#*                         File Version > <!#FV> 1.1.0 </#FV>                                                           
#*                                                                                                                      *
#</#CR>
#


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
	chmod 775 -R $(PREFIX)/$(DIR)/modules/Chart/data
	chmod +x -R $(PREFIX)/$(DIR)/*.pl