
#Build date Tuesday 4th of August 2020 18:34:41 PM
#Build revision 1.1

import os
from numpy import genfromtxt
from sys import argv
import pygal
from pygal.style import Style


root = os.path.dirname(os.path.realpath(argv[0]))

#go to script directory

os.chdir(root)

#get one level up
os.chdir("../")

#for debug
#os.chdir("../")

#get data
#values 
arrLine1 = genfromtxt('data/'+argv[1]+'_val.txt', delimiter=';')
#labels
arrLine2= genfromtxt('data/'+argv[1]+'_label.txt',dtype=None, delimiter=';',encoding='utf-8')

arrLine2= map(str,arrLine2)

custom_style = Style(
  background='transparent',
  plot_background='transparent',
  opacity='.6',
  opacity_hover='.9',
  transition='400ms ease-in',
  colors=('green', 'red'))

line_chart = pygal.StackedLine(style=custom_style,show_legend=False,x_label_rotation=270)
line_chart.width = 600
line_chart.height = 300
line_chart.title = str(argv[1])
line_chart.x_labels = arrLine2
line_chart.add('Traffic', arrLine1)
line_chart.render_to_file('pictures/'+argv[1]+'.svg')



