<?php

function ldapPplQuery($dn, $filter) {

    $ldaprdn  = 'NEWTELCOSRV\jcleese';     // ldap rdn oder dn
    $ldappass = 'N3wt3lco';  // entsprechendes password
    $ds=ldap_connect("ldap://ldap.newtelco.dev:389");  // must be a valid LDAP server!
    $r=ldap_bind($ds, $ldaprdn, $ldappass);     // this is an "anonymous" bind, typically
    $sr=ldap_search($ds, $dn, $filter);  
    $info = ldap_get_entries($ds, $sr);
    // $attrs = ldap_get_attributes($ds,$info);

    $people_list = array();

    // var_dump($info);

    for ($i=0; $i<$info["count"]; $i++) {
        $user_cn = $info[$i]["givenname"][0];
        $user_primarycomputer = $info[$i]["msds-primarycomputer"][0];
        //$userIPAddress = gethostbyname($hostname);
        $people_list[$i]['name'] = $user_cn;
        $people_list[$i]['computer'] = $user_primarycomputer;
    }

    return $people_list;
    ldap_close($ds);
}

function ldapIPQuery($dn, $filter) {

    $ldaprdn  = 'NEWTELCOSRV\jcleese';     // ldap rdn oder dn
    $ldappass = 'N3wt3lco';  // entsprechendes password
    $ds=ldap_connect("ldap://ldap.newtelco.dev:389");  // must be a valid LDAP server!
    $r=ldap_bind($ds, $ldaprdn, $ldappass);     // this is an "anonymous" bind, typically
    $sr=ldap_search($ds, $dn, $filter);  
    $info = ldap_get_entries($ds, $sr);
    // $attrs = ldap_get_attributes($ds,$info);

    $ip_list = array();

    for ($i=0; $i<$info["count"]; $i++) {
        $hostname = $info[$i]["dnshostname"][0];
        $userIPAddress = gethostbyname($hostname);
        $ip_list[] = $userIPAddress;
    }

    return json_encode($ip_list);
    ldap_close($ds);
}

if(isset($_GET['technik_computers'])){

    // Used by trello.js to get Technik Trello Scroller 
    $technikDN = "OU=Technik,OU=Users,OU=Frankfurt,dc=NEWTELCO,dc=LOCAL";
    $technikFilter = "(&(cn=*)(objectCategory=computer))";

    echo ldapIPQuery($technikDN,$technikFilter);

} else if(isset($_GET['all_computers'])) {
    $allDN = "OU=Users,OU=Frankfurt,dc=NEWTELCO,dc=LOCAL";
    $allFilter = "(&(cn=*)(objectCategory=computer))";

    echo ldapIPQuery($allDN,$allFilter);

} else if(isset($_GET['all_users'])) {
    $allDN = "OU=Users,OU=Frankfurt,dc=NEWTELCO,dc=LOCAL";
    $allFilter = "(&(cn=*)(objectCategory=person)(memberof=CN=GoogleUsers,OU=Frankfurt,DC=NEWTELCO,DC=LOCAL))";
    $computerFilter = "(&(cn=*)(objectCategory=computer))";

    $usersArr = ldapPplQuery($allDN,$allFilter);

    $output = array();

    $ldaprdn  = 'NEWTELCOSRV\jcleese';     // ldap rdn oder dn
    $ldappass = 'N3wt3lco';  // entsprechendes password
    $ds=ldap_connect("ldap://ldap.newtelco.dev:389");  // must be a valid LDAP server!
    $r=ldap_bind($ds, $ldaprdn, $ldappass);     // this is an "anonymous" bind, typically
    foreach ( $usersArr as $groupid => $fields) {
        $sr=ldap_search($ds, $fields['computer'], $computerFilter);  
        $info = ldap_get_entries($ds, $sr);
        $hostname = $info[0]["dnshostname"][0];
        $ip = gethostbyname($hostname);

        array_push($output,array($ip, $fields['name']));
    }

    ldap_close($ds);

    echo json_encode($output);

} else if(isset($_GET['technik_users'])) {
    $allDN = "OU=Technik,OU=Users,OU=Frankfurt,dc=NEWTELCO,dc=LOCAL";
    $allFilter = "(&(cn=*)(objectCategory=person)(memberof=CN=GoogleUsers,OU=Frankfurt,DC=NEWTELCO,DC=LOCAL))";

    echo ldapPplQuery($allDN,$allFilter);
}



?>
