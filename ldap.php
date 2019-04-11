<?php

$ldaprdn  = 'NEWTELCOSRV\jcleese';     // ldap rdn oder dn
$ldappass = 'N3wt3lco';  // entsprechendes password

$ds=ldap_connect("ldap://ldap.newtelco.dev:389");  // must be a valid LDAP server!

if ($ds) { 
    $r=ldap_bind($ds, $ldaprdn, $ldappass);     // this is an "anonymous" bind, typically
    $sr=ldap_search($ds, "OU=Technik,OU=Users,OU=Frankfurt,dc=NEWTELCO,dc=LOCAL", "(&(cn=*)(objectCategory=computer))");  
    $info = ldap_get_entries($ds, $sr);
    $attrs = ldap_get_attributes($ds,$info);

    $ip_list = array();

    for ($i=0; $i<$info["count"]; $i++) {
        $hostname = $info[$i]["dnshostname"][0];
        $userIPAddress = gethostbyname($hostname);
        $ip_list[] = $userIPAddress;
    }

    echo json_encode($ip_list);
    ldap_close($ds);
} else {
    echo "<h4>Unable to connect to LDAP server</h4>";
}

?>
