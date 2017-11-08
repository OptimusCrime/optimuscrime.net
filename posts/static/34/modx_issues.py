#!/usr/bin/env python
# -*- coding: utf-8 -*-

import urllib.request
from bs4 import BeautifulSoup
import matplotlib.pyplot as plt
import matplotlib.dates as mdates
import datetime as dt
import time 
import numpy as np

import gzip
import pickle

CURRENTLY_OPEN = 588
MAX_ISSUES = 6500
PAGES = 211

FETCH = False

BASE_URL = 'https://github.com/modxcms/revolution/issues?q=is%3Aissue+is%3Aclosed&page='

def load_issues_from_page(page):
	content = load_file(BASE_URL + str(page))
	return get_issues(content)

def iterate_pages():
	issues = []
	for i in range(PAGES):
		issues.extend(load_issues_from_page(i))
		print('Fetched page ' + str(i + 1) + ' of ' + str(PAGES) + '.')
		time.sleep(3)
	return issues

def format_date(obj):
	date, time = obj.split('T')
	year, month, day = map(int, date.split('-'))
	return dt.datetime(year, month, day, 0, 0, 0).date()

def get_issues(content):
	soup = BeautifulSoup(content, 'html.parser')
	html_issues = soup.find_all('relative-time')
	temp_issues = []
	for html_issue in html_issues:
		dt_object = format_date(html_issue.attrs['datetime'])
		temp_issues.append(dt_object)
	return temp_issues

def load_file(url):
	response = urllib.request.urlopen(url)
	data = response.read()
	return data.decode('utf-8')

def plot_issues(x, y):
	
	fig = plt.figure(figsize=(16, 6), dpi=80)
	ax = fig.add_subplot(111)                              
	ax.grid(which='minor', alpha=0.2)                                                
	ax.grid(which='major', alpha=0.5)   
	ax.margins(0)
	ax.tick_params(axis='x', which='major', labeltop=False, labelright=False, top=False)
	ax.tick_params(axis='x', which='minor', labeltop=False, labelright=False, top=False, bottom=False)
	ax.tick_params(axis='y', which='both', labeltop=False, labelright=True, right=True)
	ax.set_yticks(np.arange(0, MAX_ISSUES, 500))
	ax.set_ylim(ymin=0)
	ax.xaxis.set_major_formatter(mdates.DateFormatter('%Y'))
	ax.xaxis.set_major_locator(mdates.YearLocator())
	ax.xaxis.set_minor_locator(mdates.MonthLocator())

	# Bughunts
	ax.axvline(dt.datetime(2017, 7, 7, 0, 0, 0).date(), alpha=0.3, c='g')
	ax.axvline(dt.datetime(2017, 3, 3, 0, 0, 0).date(), alpha=0.3, c='g')

	ax.set_ylabel('issues')

	plt.tight_layout()

	ax.plot(x, y)
	fig.savefig('foo.png')

def create_x_axis_from_issues(issues):
	end, start = issues[0], issues[-1]
	delta = end - start
	issues_range = []
	for i in range(delta.days + 1):
		issues_range.append(start + dt.timedelta(days=i))
	return issues_range

def datetime_to_string(date):
	return str(date.strftime('%s'))

def reformat_to_data(y_axis, issues):
	values = []
	offset = CURRENTLY_OPEN
	for key, val in y_axis.items():
		if val > 0:
			offset += val
		
		values.append(offset)
	return values

def create_y_axis(issues, x_axis):
	y_axis = {}
	for x in x_axis:
		y_axis[datetime_to_string(x)] = 0

	for issue in issues:
		y_axis[datetime_to_string(issue)] += 1
	return reformat_to_data(y_axis, issues)

def populate_issues_variable():
	if FETCH:
		issues = sorted(iterate_pages(), reverse=True)
		with gzip.open('payload.pickl', 'wb') as f:
			pickle.dump(issues, f)
		return issues

	with gzip.open('payload.pickl') as f:
		return pickle.load(f)

def main():
	issues = populate_issues_variable()
	assert issues is not None
	x_axis = list(reversed(create_x_axis_from_issues(issues)))
	y_axis = create_y_axis(issues, x_axis)
	plot_issues(x_axis, y_axis)


if __name__ == '__main__':
    main()
