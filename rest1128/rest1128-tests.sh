#!/bin/bash
URL="http://ceclnx01.cec.miamioh.edu/~bowserbl/CSE383/cse383-f18/383-1024-bowserbl-1989/rest1128/rest1128.php"
keyName="bowserblTest6"
value="ThisIsMyTestValue6"

curl -o q -q ${URL}/v1/keys 2>/dev/null 
A=$(cat q)
echo  "Simple GET - should work $A"
echo
echo

curl -o q -q http://ceclnx01.cec.miamioh.edu/~${UNIQUEID}/cse383/restExample/restExample.php/v1 2>/dev/null 
A=$(cat q)
echo  "Simple GET wth a bad path- will fail $A"
echo
echo

curl -q -X 'POST' -d '{"b":"test123"}' ${URL}/v1/data 2>/dev/null >q
  A=$(cat q)
  echo  "Will fail - no 'a' present POST $A";
  echo
  echo

curl -o q -q ${URL}/v1/keys/scott 2>/dev/null 
A=$(cat q)
echo  "should return value ${A}"
echo
echo

curl -q -X 'POST' -d "{\"keyName\":\"${keyName}\",\"value\":\"${value}\"}" ${URL}/v1/keys 2>/dev/null >q
  A=$(cat q)
  echo  "Should Work POST $A";
  echo
  echo

curl -o q -q ${URL}/v1/keys/${keyName} 2>/dev/null 
A=$(cat q)
echo  "New Key should be present $A"
echo
echo


