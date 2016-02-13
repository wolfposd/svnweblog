$(document).ready(function()
{
    var hash = window.location.hash.substring(1);
    if (hash.length > 0)
    {
        sidebar(hash);
    }
});

function chevron(handle)
{
    handle = $("span", handle);
    if (handle.hasClass("glyphicon-chevron-right"))
    {
        handle.addClass("glyphicon-chevron-down").removeClass("glyphicon-chevron-right");
    } else
    {
        handle.addClass("glyphicon-chevron-right").removeClass("glyphicon-chevron-down");
    }
}

function sidebar(groupid)
{
    $("#page-content-wrapper").html("<h1>Loading...</h1>");
    $("#warning").hide();
    if (groupid < 10)
        groupid = "0" + groupid;

    $.getJSON("xml/mc" + groupid + ".json", function(json)
    {
        var content = $("#page-content-wrapper");
        if (json.authorinfo.length == 0)
        {
            showWarning(content, "File not found");
            return;
        }

        $(content).html("");
        $.get("template/overview.html", function(overviewtemplate)
        {
            $.each(json.authorinfo, function(index, info)
            {
                var tmp = $.parseHTML(overviewtemplate);
                $(tmp).find(".gravatar").attr("src", info.gravatar);
                autoJSON(tmp, info);
                $(content).append(tmp);
            });

            $(".authorinfo").wrapAll('<div class="row well"><div class="row">');

            $("#page-content-wrapper > div:nth-child(1)").append(
                    '<div class="row col-md-offset-2"><div id="canvas-holder"><canvas id="chart-area" width="300" height="300"/></div></div>');

            var pieData = [];
            $.each(json.authorinfo, function(name, info)
            {
                pieData.push({
                    "value" : info.commits,
                    "label" : info.authorname,
                    "color" : randomRGB(info.authorname)
                });
            });
            var options = {
                animationSteps : 1,
                animationEasing : "linear",
                tooltipTemplate : "<%= label%>:<%= value %>",
                onAnimationComplete : function()
                {
                    this.showTooltip(this.segments, true);
                },
                tooltipEvents : [],
                showTooltips : true
            };
            var ctx = document.getElementById("chart-area").getContext("2d");
            window.myPie = new Chart(ctx).Pie(pieData, options);

            $.get("template/revision.html", function(revision)
            {
              $insafter = $(".row.well");
              $.each(json.logentry, function(index, logentry)
              {
                  var jqob = $.parseHTML(revision.replaceAll("{{increment}}", index));
                  autoJSON(jqob, logentry);
            
                  var ulist = $(jqob).find("ul.list-group");
            
                  $.each(logentry.paths.path, function(index, path)
                  {
                      $(ulist).append(
                              '<li class="list-group-item ' + (colorStyleForCommitType(path.attributes.action))
                                      + '"><span class="badge">' + (path.attributes.action) + '</span>' + (path.attributes.nodeValue)
                                      + '</li>');
                      });
            
                      $(content).append(jqob);
                  });
              });
        });
    }).fail(function()
    {
        showWarning("#page-content-wrapper", "File not found");
    });

}

function showWarning(jqueryselector, text)
{
    $("#page-content-wrapper").html("");
    $("#warning").show();
    $("#warning>span").html(text);
}

function autoJSON(jqueryobject, json)
{
    $(jqueryobject).find(".autojson").each(function(index, handle)
    {
        var data = $(handle).data("autojson");
        $(handle).html(jsonSearch(json, data));
    });
}

function jsonSearch(json, search)
{
    var ars = search.split(".");
    var result = json;
    for (var i = 0; i < ars.length; i++)
    {
        result = result[ars[i]];
    }
    return result;
}

function hashCode(string)
{
    var hash = 0;
    if (string.length == 0)
        return hash;
    for (i = 0; i < string.length; i++)
    {
        char = string.charCodeAt(i);
        hash = ((hash << 5) - hash) + char;
        hash = hash & hash; // Convert to 32bit integer
    }
    return hash;
}

function colorStyleForCommitType(commitType)
{
    switch (commitType)
    {
    case 'A':
        return "list-group-item-success";
    case 'M':
        return "list-group-item-warning";
    case 'D':
        return "list-group-item-danger";
    case 'R':
        return "list-group-item-info";
    }
}

function randomRGB(string)
{
    var res = "#" + ("" + hashCode(string)).substring(0, 6);
    console.log(res);
    return res;
}

String.prototype.replaceAll = function(search, replace)
{
    if (replace === undefined)
    {
        return this.toString();
    }
    return this.split(search).join(replace);
}