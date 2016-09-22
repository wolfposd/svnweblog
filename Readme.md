# SVN Weblog
Displays your SVN-Log files in a nice way.





## Setup

Run a cronjob every so often polling svn info:

```bash
#!/bin/bash

DESTINATION="~/www/svn/xml"
SVNBASE="svn+ssh://myurl.com/svn/"

function getLog {
        groupid=$1
        svnURL=$SVNABSE""$groupid
        outputPath=$groupid".xml"

        svn log -v --xml $svnURL > $outputPath

        php convert.php $groupid.xml $groupid.json
        echo ""

        mv $groupid.json $DESTINATION
        rm $groupid.xml
}

# run for projects:
getLog "testproject"
getLog "otherproject"
```
