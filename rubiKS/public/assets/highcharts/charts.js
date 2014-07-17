var drawChart = function(title, subtitle, renderTo, series, categories, yText, xAxisFormatter, yAxisFormatter, tooltipFormatter, reversedYAxis)
{
	return new Highcharts.Chart({
		chart: {
			renderTo: renderTo,
			defaultSeriesType: 'spline',
			//reflow: false,
		},
		title: {
			text: title,
			x: -20 //center
		},
		subtitle: {
			text: subtitle,
			x: -20
		},
		xAxis: {
			categories: categories,
			labels: {
				step: 2,
				formatter: xAxisFormatter
			},
		},
		yAxis: {
			title: {
				text: yText
			},
			plotLines: [{
				value: 0,
				width: 1,
				color: '#808080'
			}],
			min: 0,
			labels: {
				formatter: yAxisFormatter
			},
			reversed: reversedYAxis
		},
		tooltip: {
			formatter: tooltipFormatter
		},
		legend: {
			layout: 'vertical',
			align: 'right',
			verticalAlign: 'middle',
			borderWidth: 1
		},
		series: series
	});
};

var callbackChart = function(data)
{
	var eventId = data.event,
		eventName = data.event_name,
		competitor = data.competitor,
		categories = data.date,
		renderTo = 'chart_' + eventId;

	if (eventId == '333FM') {
		var yText = 'Število potez',
			yAxisFormatter = yAxisFormatterFunction333FM,
			tooltipFormatter = tooltipFormatterFunction333FM;
	} else if (eventId == '33310MIN') {
		var yText = 'Število rešenih kock',
			yAxisFormatter = yAxisFormatterFunction33310MIN,
			tooltipFormatter = tooltipFormatterFunction33310MIN;
	} else {
		var yText = 'Čas [m:ss.cs]',
			yAxisFormatter = yAxisFormatterFunction,
			tooltipFormatter = tooltipFormatterFunction;
	}

	var reversedYAxis = (eventId == '33310MIN');

	var series = [{
		name: 'Posamezno',
		data: fixResults(data.single)
	}];
	if (typeof data.average != "undefined") {
		series.push({
			name: 'Povprečje',
			data: fixResults(data.average)
		});
	}

	return drawChart('', '', renderTo, series, categories, yText, xAxisFormatterFunction, yAxisFormatter, tooltipFormatter, reversedYAxis);
}

var loadChart = function(eventId)
{
	if (typeof loadedCharts == "undefined") loadedCharts = {};
	if (typeof loadedCharts[eventId] != "undefined") return null;
	$.ajax({
		dataType: "json",
		url: '?event=' + eventId,
		success: function(data) {
			loadedCharts[eventId] = callbackChart(data);
			return true;
		}
	});
};


var fixResults = function(results)
{
	for (var i = 0; i < results.length; i++) if (results[i] >= 77777777) results[i] = null;
	return results;
}


/* Tooltip Formatter Functions */
var tooltipFormatter = function(result, date)
{
	return '<b>' + result + '</b> (' + date + ')';
}

var tooltipFormatterFunction = function()
{
	return tooltipFormatter(parseResult(this.y), this.x);
}

var tooltipFormatterFunction333FM = function()
{
	if (this.y == null) return 'DNF';
	return tooltipFormatter(this.y + ' potez', this.x);
}

var tooltipFormatterFunction33310MIN = function()
{
	result = parseResult33310MIN(this.y);
	return tooltipFormatter(result['cubes'] + ' kock (' + result['time'] + ')', this.x);
}


/* xAxis Label Formatter Function */
var xAxisFormatterFunction = function()
{
	return this.value;
}


/* yAxis Label Formatter Functions */
var yAxisFormatterFunction = function()
{
	return parseResult(this.value);
}

var yAxisFormatterFunction333FM = function()
{
	return this.value;
}

var yAxisFormatterFunction33310MIN = function()
{
	return parseResult33310MIN(this.value)['cubes'];
}


/* Parse results */
var parseResult = function(result)
{
	var cs = result % 100,
		x = Math.floor(result / 100),
		s = x % 60,
		m = Math.floor(x / 60),
		str = '';

	if (m > 0) str += m + ':';
	str += (s < 10) ? '0' + s : s;
	str += '.';
	str += (cs < 10) ? '0' + cs : cs;

	return str;
}

var parseResult33310MIN = function(result)
{
	result = result.toString();
	return {
		cubes: 400 - parseInt(result.substring(0, 3)),
		time: parseResult(result.substring(3))
	}
}