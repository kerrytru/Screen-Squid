

#Build date Tuesday 21st of July 2020 11:54:50 AM
#Build revision 1.0

import os
from numpy import genfromtxt
from sys import argv
import pygal



root = os.getcwd()

#go to script directory

os.chdir(root)

#get one level up
os.chdir("../modules/Chart/")

#for debug
#os.chdir("../")

#get data
#values 
arrLine1 = genfromtxt('data/'+argv[1]+'_val.txt', delimiter=';')
#labels
arrLine2= genfromtxt('data/'+argv[1]+'_label.txt', dtype=None, delimiter=';')

pie_chart = pygal.Pie()
pie_chart.title = str(argv[1])
pie_chart.width = 600
pie_chart.height = 300

for i in range(len(arrLine2)):
  pie_chart.add(arrLine2[i], arrLine1[i])

pie_chart.render_to_file('pictures/'+argv[1]+'.svg')


