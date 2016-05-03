/*
 * Chartify template object
 */

var chartify = (function($) {
    var module = {};
    
    /*
     * Creates a simple chart
     * 
     * @param string id
     * @param object options
     * @returns object chart
     */
    var createChart = function(id, options, animation) {
        // Get context
        var ctx = document.getElementById(id).getContext('2d');

        // Turns animation off (For resize purposes)
        var animationOption = animation == false ? false : options.plugin.animation == 'true' ? true : false;
        
        // Init charts
        var chart = new Chart(ctx)[options.type](options.data, {
            responsive: true,
            animation: animationOption,
            animationEasing: options.plugin.animationEasing,
            tooltipTemplate: tooltipTemplate(options),
            tooltipFillColor: options.plugin.tooltipBgColor,
            tooltipFontColor: options.plugin.tooltipFontColor,
            tooltipTitleFontColor: options.plugin.tooltipFontColor,
            legendTemplate : legendTemplate(options)
        });

        // Add percentages to segments
        $.each(chart.segments, function(index) {
            this.percentage = options.data[index].percentage;
        });

        chart.update();
        chart.$el = $('#' + id).closest('.canvas-holder');

        // Generates legend map
        generateLegend(chart, options.plugin.map);

        // append instance and refresh function to the chart itself
        chart.$el[0].chartInstance = chart;
        chart.$el[0].size = options.plugin.size;
        chart.$el[0].options = options;
        chart.$el[0].elId = id;

        return chart;
    }
    
    /*
     * Generates tooltip template
     * 
     * @param object options
     * @returns string template
     */
    var tooltipTemplate = function(options) {
        // Preparing tooltip template
        var template = '';
        var votesExpression = '<%if (value == 0){%>' + options.localization.novotes.replace('%s', '<%= value %>')
                + '<% }else if (value == 1){ %>' + options.localization.vote.replace('%s', '<%= value %>')
                + '<% }else{ %>' + options.localization.votes.replace('%s', '<%= value %>') 
                + '<% } %>';
        
        // Displays label when no map selected
        if (options.plugin.map == 'none') {
            template = '<%if (label){%><%=label%>: <%}%>';
        }
        

        switch (options.results) {
            case 'number':
            default:
                template += votesExpression;
                break;
            case 'percentage':
                template += '<%= percentage %>%';
                break;
            case 'both':
                template += '<%= percentage %>% • ' +  votesExpression;
                break;
            case 'nothing':
                template = '<%if (label){%><%=label%><%}%>';
                break;
        }

        return template;
    }
    
    /**
     * Generates legend template
     * 
     * @param {type} options
     * @returns {String}
     */
    var legendTemplate = function(options) {
        var template = '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<segments.length; i++){%><li><span style="background-color:<%=segments[i].fillColor%>"></span>';
        var votesExpression = '<%if (segments[i].value == 0){%>' + options.localization.novotes.replace('%s', '<%= segments[i].value %>')
                + '<% }else if (segments[i].value == 1){ %>' + options.localization.vote.replace('%s', '<%= segments[i].value %>')
                + '<% }else{ %>' + options.localization.votes.replace('%s', '<%= segments[i].value %>') 
                + '<% } %>';
        
        template += '<%if(segments[i].label){%><%=segments[i].label%><%}%> ( ';

        switch (options.results) {
            case 'number':
            default:
                template += votesExpression;
                break;
            case 'percentage':
                template += '<%= segments[i].percentage %>%';
                break;
            case 'both':
                template += '<%= segments[i].percentage %>% • ' +  votesExpression;
                break;
        }
        
        template += ' )</li><%}%></ul>';
        
        return template;
    }
    
    /*
     * Generates legend inside the map holder
     * 
     * @param object chart
     * @param string mapType
     * @returns void
     */
    var generateLegend = function(chart, mapType) {
        var $mapHolder = chart.$el.siblings('.map-holder');
        $mapHolder.html(chart.generateLegend());
        switch (mapType) {
            case 'none':
                $mapHolder.addClass('none');
                break;
            case 'below':
            default:
                $mapHolder.addClass('below');
                break;
            case 'float':
                $mapHolder.addClass('float');
                break;
        }

        // On map elements hover
        if( mapType != 'none' ) {
            Chart.helpers.each($mapHolder[0].firstChild.childNodes, function(legendNode, index) {
                Chart.helpers.addEvent(legendNode, 'mouseover', function() {
                    var activeSegment = chart.segments[index];
                    activeSegment.save();
                    activeSegment.fillColor = activeSegment.highlightColor;
                    chart.showTooltip([activeSegment]);
                    activeSegment.restore();
                });
            });
            Chart.helpers.addEvent($mapHolder[0], 'mouseout', function() {
                chart.draw();
            });
        }
    }
    
    /*
     * Initialize charts
     * 
     * @param string id
     * @param string type
     * @param object data
     * @param object plugin
     * @param string results
     * @param object localization
     * @returns void
     */
    module.init = function(id, type, data, plugin, results, localization) {
        
        // Remove it later
        var options = {
            type: type,
            localization: localization,
            plugin: plugin,
            data: data,
            results: results
        }
        
        createChart(id, options, true);
        //chartInstance.$el[0].refresh();
    }

    return module;
})(jQuery);