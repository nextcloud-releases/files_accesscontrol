Feature: Sharing user
  Background:
    Given user "test1" exists
    Given as user "test1"
    Given user "test2" exists
    And using new dav path

  Scenario: Creating file is blocked
    Given user "admin" creates global flow with 200
    | name      | Admin flow                       |
    | class     | OCA\FilesAccessControl\Operation |
    | entity    | OCA\WorkflowEngine\Entity\File   |
    | events    | []                               |
    | operation | deny                             |
    | checks-0  | {"class":"OCA\\WorkflowEngine\\Check\\FileName", "operator": "is", "value": "foobar.txt"} |
    And User "test1" uploads file "data/textfile.txt" to "/foobar.txt"
    Then The webdav response should have a status code "403"

  Scenario: Downloading file is blocked
    Given User "test1" uploads file "data/textfile.txt" to "/foobar.txt"
    And The webdav response should have a status code "201"
    And user "test1" shares file "/foobar.txt" with user "test2"
    And as user "test2"
    When Downloading file "/foobar.txt"
    Then The webdav response should have a status code "200"
    When Downloading file "/foobar.txt" with range "1-4"
    Then The webdav response should have a status code "200"
    And user "admin" creates global flow with 200
      | name      | Admin flow                       |
      | class     | OCA\FilesAccessControl\Operation |
      | entity    | OCA\WorkflowEngine\Entity\File   |
      | events    | []                               |
      | operation | deny                             |
      | checks-0  | {"class":"OCA\\WorkflowEngine\\Check\\FileName", "operator": "is", "value": "foobar.txt"} |
    And as user "test2"
    When Downloading file "/foobar.txt"
    Then The webdav response should have a status code "403"
    When Downloading file "/foobar.txt" with range "1-4"
    Then The webdav response should have a status code "403"

  Scenario: Updating file is blocked
    Given User "test1" uploads file "data/textfile.txt" to "/foobar.txt"
    And The webdav response should have a status code "201"
    And user "test1" shares file "/foobar.txt" with user "test2"
    And user "admin" creates global flow with 200
      | name      | Admin flow                       |
      | class     | OCA\FilesAccessControl\Operation |
      | entity    | OCA\WorkflowEngine\Entity\File   |
      | events    | []                               |
      | operation | deny                             |
      | checks-0  | {"class":"OCA\\WorkflowEngine\\Check\\FileName", "operator": "is", "value": "foobar.txt"} |
    And as user "test2"
    When User "test2" uploads file "data/textfile-2.txt" to "/foobar.txt"
    Then The webdav response should have a status code "403"

  Scenario: Deleting file that is shared directly is not blocked because it unshares from self
    Given User "test1" uploads file "data/textfile.txt" to "/foobar.txt"
    And The webdav response should have a status code "201"
    And user "test1" shares file "/foobar.txt" with user "test2"
    And user "admin" creates global flow with 200
    | name      | Admin flow                       |
    | class     | OCA\FilesAccessControl\Operation |
    | entity    | OCA\WorkflowEngine\Entity\File   |
    | events    | []                               |
    | operation | deny                             |
    | checks-0  | {"class":"OCA\\WorkflowEngine\\Check\\FileName", "operator": "is", "value": "foobar.txt"} |
    And as user "test2"
    When User "test2" deletes file "/foobar.txt"
    Then The webdav response should have a status code "204"

  Scenario: Deleting file inside a shared folder is blocked
    Given User "test1" created a folder "/subdir"
    Given User "test1" uploads file "data/textfile.txt" to "/subdir/foobar.txt"
    And The webdav response should have a status code "201"
    And user "test1" shares file "/subdir" with user "test2"
    And user "admin" creates global flow with 200
    | name      | Admin flow                       |
    | class     | OCA\FilesAccessControl\Operation |
    | entity    | OCA\WorkflowEngine\Entity\File   |
    | events    | []                               |
    | operation | deny                             |
    | checks-0  | {"class":"OCA\\WorkflowEngine\\Check\\FileName", "operator": "is", "value": "foobar.txt"} |
    And as user "test2"
    When User "test2" deletes file "/subdir/foobar.txt"
    Then The webdav response should have a status code "403"
