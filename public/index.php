<?php 

    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;
    require '../src/vendor/autoload.php';

    use \Firebase\JWT\JWT;
    use \Firebase\JWT\Key;

    $app = new \Slim\App;

    // Register
    $app->post('/user/register', function (Request $request, Response $response, array $args) {

        $data = json_decode($request->getBody(), false);
        
        // Check if data is an array (multiple users)
        if (!is_array($data)) {
            $data = [$data];
        }
    
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "library";
    
        $responses = [];
    
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            foreach ($data as $user) {
                $uname = $user->username;
                $pass = $user->password;
    
                if (empty($uname) || empty($pass)) {
                    $responses[] = array("status" => "fail", "username" => $uname, "data" => array("Message" => "Username and password cannot be empty."));
                    continue;
                }
    
                $sql = "SELECT userid FROM users WHERE username = :username";
                $statement = $conn->prepare($sql);
                $statement->execute(['username' => $uname]);
                $existing_username = $statement->fetch(PDO::FETCH_ASSOC);
    
                if ($existing_username) {
                    $responses[] = array("status" => "fail", "username" => $uname, "data" => array("Message" => "Username already taken!"));
                    continue;
                }

                $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
                $statement = $conn->prepare($sql);

                $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);
    
                $statement->execute([
                    ':username' => $uname,
                    ':password' => $hashedPassword,
                ]);
    
                $responses[] = array("status" => "success", "username" => $uname, "data" => null);
            }
    
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(array("status" => "fail", "data" => array("Message" => "Registration failed!"))));
            error_log($e->getMessage());
        }
    
        $response->getBody()->write(json_encode($responses)); 
        return $response;
    });    

    // Login 
    $app->post('/user/login', function (Request $request, Response $response, array $args) {
        $data = json_decode($request->getBody());
        
        $password = $data->password;
        $uname = $data->username; 

        $servername = "localhost";
        $dbpassword = ""; 
        $username = "root";
        $dbname = "library";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $dbpassword);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Updated query to use username instead of email
            $sql = "SELECT userid, username, password, access_level FROM users WHERE username = :username";
            $statement = $conn->prepare($sql);
            $statement->execute(['username' => $uname]);

            $user = $statement->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                
                $key = 'key';
                $expire = time();
                
                if ($user['access_level'] == "admin") {
                    $payload = [
                        'iss' => 'http://library.org',
                        'aud' => 'http://library.com',
                        'iat' => $expire,
                        'exp' => $expire + 3600,
                        'data' => array(
                            'userid' => $user['userid'], 
                            "name" => $user['username'],
                            "access_level" => $user['access_level']
                        )
                    ];

                    $jwt = JWT::encode($payload, $key, 'HS256');

                    $updateSql = "UPDATE users SET token = :token WHERE userid = :userid";
                    $updateStatement = $conn->prepare($updateSql);
                    $updateStatement->execute(['token' => $jwt, 'userid' => $user['userid']]);

                    $response->getBody()->write(
                        json_encode(array("status" => "success", "token" => $jwt))
                    );

                } elseif (empty($user['access_level'])) {
                    $payload = [
                        'iss' => 'http://library.org',
                        'aud' => 'http://library.com',
                        'iat' => $expire,
                        'exp' => $expire + 3600,
                        'data' => array(
                            'userid' => $user['userid'], 
                            "name" => $user['username'],
                            "access_level" => $user['access_level']
                        )
                    ];

                    $jwt = JWT::encode($payload, $key, 'HS256');

                    $tokenInsrt = "UPDATE users SET token = :token WHERE userid = :userid";
                    $updateStatement = $conn->prepare($tokenInsrt);
                    $updateStatement->execute(['token' => $jwt, 'userid' => $user['userid']]);

                    $response->getBody()->write(
                        json_encode(array("status" => "success", "token" => $jwt))
                    );

                } else {
                    $response->getBody()->write(
                        json_encode(array("status" => "fail", "data" => array("Message" => "Access Denied.")))
                    );
                }
            } else {
                $response->getBody()->write(
                    json_encode(array("status" => "fail", "data" => array("Message" => "Invalid username or password"))),
                );
            }
        } catch (Exception $e) {
            $response->getBody()->write(
                json_encode(array("status" => "fail", "data" => array("Message" => "Login failed.")))
            );
            error_log($e->getMessage());
        }

        $conn = null;
        return $response;
    });
 
    //Authors API
    //Add Author (Admin)
    $app->post("/add/authors", function (Request $request, Response $response, array $args) {
        $data = json_decode($request->getBody());

        $authors = $data->authors;
        $jwt = $data->token;

        $servername = "localhost";
        $password = "";
        $username = "root";
        $dbname = "library";
        $key = 'key';

        try {
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
            if (!isset($decoded->data->access_level) || $decoded->data->access_level !== 'admin') {
                $response->getBody()->write(
                    json_encode(array(
                        "status" => "fail",
                        "message" => "Access denied, only admins can add authors."
                    ))
                );
                return $response;
            }

            try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $userid = $decoded->data->userid;
                $access_level = $decoded->data->access_level;

                $successful_authors = [];
                $failed_authors = [];

                foreach ($authors as $authorname) {
                    // Check if author already exists
                    $sql = "SELECT authorid FROM authors WHERE authorname = :authorname";
                    $statement = $conn->prepare($sql);
                    $statement->execute(['authorname' => $authorname]);
                    $existing_author = $statement->fetch(PDO::FETCH_ASSOC);

                    if ($existing_author) {
                        $failed_authors[] = array(
                            "authorname" => $authorname,
                            "message" => "Author already exists."
                        );
                        continue;
                    }

                    // Insert the new author
                    $sql = "INSERT INTO authors (authorname) VALUES (:authorname)";
                    $statement = $conn->prepare($sql);
                    $statement->execute([":authorname" => $authorname]);

                    $successful_authors[] = $authorname;
                }

                // Generate a new JWT token
                $expire = time();
                $payload = [
                    'iss' => 'http://library.org',
                    'aud' => 'http://library.com',
                    'iat' => $expire,
                    'exp' => $expire + 3600,
                    'data' => array(
                        'userid' => $userid,
                        'name' => $decoded->data->name,
                        'access_level' => $access_level
                    )
                ];

                $new_jwt = JWT::encode($payload, $key, 'HS256');

                // Update the user's token in the database
                $sql = "UPDATE users SET token = :token WHERE userid = :userid";
                $statement = $conn->prepare($sql);
                $statement->execute(['token' => $new_jwt, 'userid' => $userid]);

                // Return the response with the new token
                $response->getBody()->write(json_encode(array(
                    "status" => "success",
                    "message" => "Authors processed successfully.",
                    "successful_authors" => implode(", ", $successful_authors),
                    "failed_authors" => implode(", ", array_map(function ($author) {
                        return $author['authorname'] . " (" . $author['message'] . ")";
                    }, $failed_authors)),
                    "new_token" => $new_jwt
                )));

            } catch (PDOException $e) {
                $response->getBody()->write(json_encode(array(
                    "status" => "fail",
                    "message" => $e->getMessage()
                )));
            }

        } catch (Exception $e) {
            $response->getBody()->write(json_encode(array(
                "status" => "fail",
                "message" => $e->getMessage()
            )));
        }

        $conn = null;
        return $response;
    });
     
    //Update Author (Admin)
    $app->post("/update/authors", function(Request $request, Response $response, array $args) {
        $data = json_decode($request->getBody());
    
        $authorid = $data->authorid !== '' ? $data->authorid : null;
        $authorname = $data->authorname !== '' ? $data->authorname : null;
    
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "library";
    
        $key = 'key';
        $jwt = $data->token;
    
        try {
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
    
            if (!isset($decoded->data->access_level) || $decoded->data->access_level !== 'admin') {
                $response->getBody()->write(json_encode(array(
                    "status" => "fail",
                    "message" => "Access denied, only admins can update authors."
                )));
                return $response;
            }
    
            try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                $userid = $decoded->data->userid;
                $access_level = $decoded->data->access_level;
    
                $sql = "SELECT username, password, token FROM users WHERE userid = :userid";
                $statement = $conn->prepare($sql);
                $statement->execute(['userid' => $userid]);
                $userInfo = $statement->fetch(PDO::FETCH_ASSOC);
    
                if ($userInfo['token'] !== $jwt) {
                    $response->getBody()->write(json_encode(array(
                        "status" => "fail",
                        "message" => "Invalid or outdated token."
                    )));
                    return $response;
                }
    
                $sql = "SELECT * FROM authors WHERE authorid = :authorid";
                $statement = $conn->prepare($sql);
                $statement->execute(['authorid' => $authorid]);
                $existing_authorid = $statement->fetch(PDO::FETCH_ASSOC);
    
                if (!$existing_authorid) {
                    $response->getBody()->write(json_encode(array(
                        "status" => "fail",
                        "message" => "Invalid Author ID."
                    )));
                    return $response;
                }
    
                $fields = [];
                $newValue = [];
    
                if ($authorname !== null) {
                    $fields[] = "authorname = :authorname";
                    $newValue[':authorname'] = $authorname;
                }
    
                if (empty($fields)) {
                    $response->getBody()->write(json_encode(array(
                        "status" => "fail",
                        "message" => "No fields to update."
                    )));
                    return $response;
                }
    
                $sql = "UPDATE authors SET " . implode(", ", $fields) . " WHERE authorid = :authorid";
                $statement = $conn->prepare($sql);
    
                foreach ($newValue as $param => $value) {
                    $statement->bindValue($param, $value);
                }
                $statement->bindValue(':authorid', $authorid);
    
                $statement->execute();
    
                $expire = time();
                $payload = [
                    'iss' => 'http://library.org',
                    'aud' => 'http://library.com',
                    'iat' => $expire,
                    'exp' => $expire + 3600,
                    'data' => array(
                        'userid' => $userid,
                        "name" => $userInfo['username'],
                        "access_level" => $access_level
                    )
                ];
    
                $new_jwt = JWT::encode($payload, $key, 'HS256');
    
                $sql = "UPDATE users SET token = :token WHERE userid = :userid";
                $statement = $conn->prepare($sql);
                $statement->execute(['token' => $new_jwt, 'userid' => $userid]);
    
                $response->getBody()->write(json_encode(array(
                    "status" => "success",
                    "message" => "Author updated successfully.",
                    "updated_author" => array(
                        "authorid" => $authorid,
                        "authorname" => $authorname
                    ),
                    "new_token" => $new_jwt
                )));
    
            } catch (PDOException $e) {
                $response->getBody()->write(json_encode(array(
                    "status" => "fail",
                    "message" => $e->getMessage()
                )));
            }
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(array(
                "status" => "fail",
                "message" => $e->getMessage()
            )));
        }
    
        $conn = null;
        return $response;
    });
    

    //Delete Author (Admin)
    $app->delete("/delete/authors", function(Request $request, Response $response, array $args) {
        $data = json_decode($request->getBody());
        
        $authorid = $data->authorid;
        
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "library";
        
        $key = 'key';
        $jwt = $data->token;
        
        try {
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
        
            if (!isset($decoded->data->access_level) || $decoded->data->access_level !== 'admin') {
                $response->getBody()->write(json_encode([
                    "status" => "fail",
                    "message" => "Access denied. Only admins can delete authors."
                ]));
                return $response;
            }
        
            try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                $userid = $decoded->data->userid;
                $access_level = $decoded->data->access_level;
    
                // Verify user token
                $sql = "SELECT username, password, token FROM users WHERE userid = :userid";
                $statement = $conn->prepare($sql);
                $statement->execute(['userid' => $userid]);
                $userInfo = $statement->fetch(PDO::FETCH_ASSOC);
    
                if ($userInfo['token'] !== $jwt) {
                    $response->getBody()->write(json_encode([
                        "status" => "fail",
                        "message" => "Invalid or outdated token."
                    ]));
                    return $response;
                }
        
                // Check if the author exists
                $sql = "SELECT * FROM authors WHERE authorid = :authorid";
                $statement = $conn->prepare($sql);
                $statement->execute(['authorid' => $authorid]);
                $existingAuthor = $statement->fetch(PDO::FETCH_ASSOC);
        
                if ($existingAuthor) {
                    // Delete the author
                    $sql = "DELETE FROM authors WHERE authorid = :authorid";
                    $statement = $conn->prepare($sql);
                    $statement->execute(['authorid' => $authorid]);
    
                    // Generate a new token
                    $expire = time();
                    $payload = [
                        'iss' => 'http://library.org',
                        'aud' => 'http://library.com',
                        'iat' => $expire,
                        'exp' => $expire + 3600,
                        'data' => [
                            'userid' => $userid,
                            "name" => $userInfo['username'],
                            "access_level" => $access_level
                        ]
                    ];
    
                    $new_jwt = JWT::encode($payload, $key, 'HS256');
    
                    // Update the token in the database
                    $sql = "UPDATE users SET token = :token WHERE userid = :userid";
                    $statement = $conn->prepare($sql);
                    $statement->execute(['token' => $new_jwt, 'userid' => $userid]);
    
                    $response->getBody()->write(json_encode([
                        "status" => "success",
                        "message" => "Author deleted successfully.",
                        "new_token" => $new_jwt
                    ]));
                } else {
                    $response->getBody()->write(json_encode([
                        "status" => "fail",
                        "message" => "Invalid Author ID."
                    ]));
                    return $response;
                }
            } catch (PDOException $e) {
                $response->getBody()->write(json_encode([
                    "status" => "fail",
                    "message" => $e->getMessage()
                ]));
            }
        } catch (Exception $e) {
            $response->getBody()->write(json_encode([
                "status" => "fail",
                "message" => $e->getMessage()
            ]));
        }
        
        $conn = null;
        return $response;
    });
    

    //Display all Authors 
    $app->get("/display/authors", function (Request $request, Response $response, array $args) {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "library";
    
        $key = 'key';
        $data = json_decode($request->getBody());
        $jwt = $data->token;
    
        try {
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
    
            try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                $userid = $decoded->data->userid;
                $access_level = $decoded->data->access_level;
    
                // Verify the token
                $sql = "SELECT username, password, token FROM users WHERE userid = :userid";
                $statement = $conn->prepare($sql);
                $statement->execute(['userid' => $userid]);
                $userInfo = $statement->fetch(PDO::FETCH_ASSOC);
    
                if ($userInfo['token'] !== $jwt) {
                    $response->getBody()->write(json_encode([
                        "status" => "fail",
                        "message" => "Invalid or outdated token."
                    ]));
                    return $response;
                }
    
                // Fetch all authors
                $sql = "SELECT * FROM authors";
                $statement = $conn->query($sql);
                $authorsCount = $statement->rowCount();
                $authors = $statement->fetchAll(PDO::FETCH_ASSOC);
    
                if ($authorsCount > 0) {
                    // Generate a new token
                    $expire = time();
                    $payload = [
                        'iss' => 'http://library.org',
                        'aud' => 'http://library.com',
                        'iat' => $expire,
                        'exp' => $expire + 3600,
                        'data' => [
                            'userid' => $userid,
                            "name" => $userInfo['username'],
                            "access_level" => $access_level
                        ]
                    ];
    
                    $new_jwt = JWT::encode($payload, $key, 'HS256');
    
                    // Update the token in the database
                    $sql = "UPDATE users SET token = :token WHERE userid = :userid";
                    $statement = $conn->prepare($sql);
                    $statement->execute(['token' => $new_jwt, 'userid' => $userid]);
    
                    // Send success response with authors and new token
                    $response->getBody()->write(json_encode([
                        "status" => "success",
                        "authors" => $authors,
                        "new_token" => $new_jwt
                    ]));
                } else {
                    $response->getBody()->write(json_encode([
                        "status" => "fail",
                        "message" => "No authors found."
                    ]));
                }
            } catch (PDOException $e) {
                $response->getBody()->write(json_encode([
                    "status" => "fail",
                    "message" => $e->getMessage()
                ]));
            }
        } catch (Exception $e) {
            $response->getBody()->write(json_encode([
                "status" => "fail",
                "message" => $e->getMessage()
            ]));
        }
    
        $conn = null;
        return $response;
    });
       

    //Users API

    //Display all Users (Admin)
    $app->get("/displayall/users", function (Request $request, Response $response, array $args) {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "library";

        $key ='key';
        $data=json_decode($request->getBody());
        $jwt=$data->token;

        try {
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

            if (!isset($decoded->data->access_level) || $decoded->data->access_level !== 'admin') {
                $response->getBody()->write(
                    json_encode(array("status" => "fail", "data" => array("Message" => "Access denied, only admins can view list of users.")))
                );
                return $response;
            }

            try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $userid = $decoded->data->userid;
                $access_level = $decoded->data->access_level;

                $sql = "SELECT username, password, token FROM users WHERE userid = :userid";
                $statement = $conn->prepare($sql);
                $statement->execute(['userid' => $userid]);
                $userInfo = $statement->fetch(PDO::FETCH_ASSOC);

                if ($userInfo['token'] !== $jwt) {
                    $response->getBody()->write(
                        json_encode(array("status" => "fail", "data" => array("Message" => "Invalid or Outdated Token.")))
                    );
                    return $response;
                }

                $sql = "SELECT username, email, created_at FROM users";
                $statement = $conn->query($sql);
                $usersCount = $statement->rowCount();
                $displayUsers = $statement->fetchAll(PDO::FETCH_ASSOC);

                $key = 'key';
                $expire = time();

                if ($usersCount > 0) {
                    $payload = [
                        'iss' => 'http://library.org',
                        'aud' => 'http://library.com',
                        'iat' => $expire,
                        'exp' => $expire + 3600,
                        'data' => array(
                            'userid' => $userid, 
                            "name" => $username,
                            "access_level" => $access_level
                        )
                    ];

                    $new_jwt = JWT::encode($payload, $key, 'HS256');

                    $sql = "UPDATE users SET token = :token  WHERE userid = :userid";
                    $statement = $conn->prepare($sql);
                    $statement->execute(['token' => $new_jwt, 'userid' => $userid]);

                    $response->getBody()->write(
                        json_encode(array("status" => "success", "new_token" => $new_jwt, "data" => $displayUsers))
                    );
                } else {
                    $payload = [
                        'iss' => 'http://library.org',
                        'aud' => 'http://library.com',
                        'iat' => $expire,
                        'exp' => $expire + 3600,
                        'data' => array(
                            'userid' => $userid, 
                            "name" => $username,
                            "access_level" => $access_level
                        )
                    ];

                    $new_jwt = JWT::encode($payload, $key, 'HS256');

                    $sql = "UPDATE users SET token = :token  WHERE userid = :userid";
                    $statement = $conn->prepare($sql);
                    $statement->execute(['token' => $new_jwt, 'userid' => $userid]);

                    $response->getBody()->write(
                        json_encode(array("status" => "success", "new_token" => $new_jwt, "Message" => "No user accounts found."))
                    );
                }

            } catch (PDOException $e) {
                $response->getBody()->write(json_encode(array("status" => "fail", "data" => array("Message" => $e->getMessage()))));
            }
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(array("status" => "fail", "data" => array("Message" => $e->getMessage()))));
        }

        $conn = null;
        return $response;
    });

    //Delete User(Admin)
    $app->delete("/delete/users", function(Request $request, Response $response, array $args) {
        $data = json_decode($request->getBody());
    
        $userid = $data->userid;
    
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "library";
    
        $key = 'key';
        $jwt = $data->token;
    
        try {
            $decoded = jwt::decode($jwt, new Key($key, 'HS256'));

            if (!isset($decoded->data->access_level) || $decoded->data->access_level !== 'admin') {
                $response->getBody()->write(
                    json_encode(array("status" => "fail", "data" => array("title" => "Access denied, only admins can delete user.")))
                );
                return $response;
            }

            try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $adminid = $decoded->data->userid;
                $access_level = $decoded->data->access_level;

                $sql = "SELECT username, password, token FROM users WHERE userid = :userid";
                $statement = $conn->prepare($sql);
                $statement->execute(['userid' => $adminid]);
                $userInfo = $statement->fetch(PDO::FETCH_ASSOC);

                if ($userInfo['token'] !== $jwt) {
                    $response->getBody()->write(
                        json_encode(array("status" => "fail", "data" => array("Message" => "Invalid or Outdated Token.")))
                    );
                    return $response;
                }

                $sql = "SELECT * FROM users WHERE userid = :userid";
                $statement = $conn->prepare($sql);
                $statement->execute(['userid' => $userid]);
                $existing_user = $statement->fetch(PDO::FETCH_ASSOC);

                if ($existing_user) {
                    if ($existing_user['access_level'] === 'admin' && !empty($existing_user['access_level'])) {
                        $response->getBody()->write(
                            json_encode(array("status" => "fail", "data" => array("Message" => "Admin accounts can't be deleted.")))
                        );
                        return $response->withStatus(403);
                    } else {

                        $sql = "DELETE FROM users WHERE userid = :userid";
                        $statement = $conn->prepare($sql);
                        $statement->execute(['userid' => $userid]);

                        $key = 'key';
                        $expire = time();

                        $payload = [
                            'iss' => 'http://library.org',
                            'aud' => 'http://library.com',
                            'iat' => $expire,
                            'exp' => $expire + 3600,
                            'data' => array(
                                'userid' => $userid, 
                                "name" => $username,
                                "access_level" => $access_level
                            )
                        ];

                        $new_jwt = JWT::encode($payload, $key, 'HS256');

                        $sql = "UPDATE users SET token = :token  WHERE userid = :userid";
                        $statement = $conn->prepare($sql);
                        $statement->execute(['token' => $new_jwt, 'userid' => $userid]);

                        $response->getBody()->write(
                            json_encode(array("status" => "success", "new_token" => $new_jwt))
                        );
                    }

                } else {
                    $response->getBody()->write(
                        json_encode(array("status" => "fail", "data" => array("Message" => "Invalid User ID.")))
                    );
                    return $response;
                }
            } catch (PDOException $e) {
                $response->getBody()->write(json_encode(array("status"=>"fail", "data"=> array("Message"=>$e->getMessage()))));
            }
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(array("status" => "fail", "data" => array("Message"=>$e->getMessage()))));
        }
    
        $conn = null;
        return $response;
    });

    
        //Books API
    //Add Book (Admin) 
    $app->post("/add/books", function(Request $request, Response $response, array $args) {
        $data = json_decode($request->getBody());
        
        // Retrieve the array of books
        $books = $data->books; // Expected to be an array of book objects
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "library";
        
        $key = 'key';
        $jwt = $data->token;
        
        try {
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
            
            // Check access level
            if (!isset($decoded->data->access_level) || $decoded->data->access_level !== 'admin') {
                $response->getBody()->write(json_encode([
                    "status" => "fail",
                    "message" => "Access denied, only admins can add books."
                ]));
                return $response;
            }
            
            try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $userid = $decoded->data->userid;
                $access_level = $decoded->data->access_level;
                
                // Validate token
                $sql = "SELECT username, password, token FROM users WHERE userid = :userid";
                $statement = $conn->prepare($sql);
                $statement->execute(['userid' => $userid]);
                $userInfo = $statement->fetch(PDO::FETCH_ASSOC);
                
                if ($userInfo['token'] !== $jwt) {
                    $response->getBody()->write(json_encode([
                        "status" => "fail",
                        "message" => "Invalid or outdated token."
                    ]));
                    return $response;
                }
                
                // Prepare SQL queries
                $insertBookSQL = "INSERT INTO books (title, genre, authorid, bookCode) VALUES (:title, :genre, :authorid, :bookCode)";
                $insertCollectionSQL = "INSERT INTO books_collection (bookid, authorid) VALUES (:bookid, :authorid)";
                $authorMap = [];
                
                // Process each book
                foreach ($books as $book) {
                    $author = $book->author;
                    $title = $book->title;
                    $genre = $book->genre;
                    
                    // Check if author exists
                    if (!isset($authorMap[$author])) {
                        $sql = "SELECT authorid FROM authors WHERE authorname = :author";
                        $statement = $conn->prepare($sql);
                        $statement->execute(['author' => $author]);
                        $existing_author = $statement->fetch(PDO::FETCH_ASSOC);
                        
                        if (!$existing_author) {
                            // Insert new author
                            $sql = "INSERT INTO authors (authorname) VALUES (:author)";
                            $statement = $conn->prepare($sql);
                            $statement->execute(['author' => $author]);
                            $authorid = $conn->lastInsertId();
                        } else {
                            $authorid = $existing_author['authorid'];
                        }
                        $authorMap[$author] = $authorid;
                    } else {
                        $authorid = $authorMap[$author];
                    }
                    
                    // Generate unique bookCode
                    $numbers = rand(100, 999);
                    $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $letterCode = $letters[rand(0, strlen($letters) - 1)] . $letters[rand(0, strlen($letters) - 1)];
                    $bookCode = $numbers . $letterCode;
                    
                    // Insert book
                    $statement = $conn->prepare($insertBookSQL);
                    $statement->execute(['title' => $title, 'genre' => $genre, 'authorid' => $authorid, 'bookCode' => $bookCode]);
                    $bookid = $conn->lastInsertId();
                    
                    // Insert into books_collection
                    $stmnt = $conn->prepare($insertCollectionSQL);
                    $stmnt->execute(['bookid' => $bookid, 'authorid' => $authorid]);
                }
                
                // Generate new token
                $expire = time();
                $payload = [
                    'iss' => 'http://library.org',
                    'aud' => 'http://library.com',
                    'iat' => $expire,
                    'exp' => $expire + 3600,
                    'data' => [
                        'userid' => $userid,
                        "name" => $userInfo['username'],
                        "access_level" => $access_level
                    ]
                ];
                $new_jwt = JWT::encode($payload, $key, 'HS256');
                
                // Update token in the database
                $sql = "UPDATE users SET token = :token WHERE userid = :userid";
                $statement = $conn->prepare($sql);
                $statement->execute(['token' => $new_jwt, 'userid' => $userid]);
                
                // Success response
                $response->getBody()->write(json_encode([
                    "status" => "success",
                    "message" => "Books successfully added.",
                    "new_token" => $new_jwt
                ]));
            } catch (PDOException $e) {
                $response->getBody()->write(json_encode([
                    "status" => "fail",
                    "message" => $e->getMessage()
                ]));
            }
        } catch (Exception $e) {
            $response->getBody()->write(json_encode([
                "status" => "fail",
                "message" => $e->getMessage()
            ]));
        }
        
        $conn = null;
        return $response;
    });
    
    
    //Update Book (Admin)
    $app->post("/update/books", function(Request $request, Response $response, array $args) {
        $data = json_decode($request->getBody());
    
        $bookCode = $data->bookCode;
        $author = $data->author !== '' ? $data->author : null;
        $title = $data->title !== '' ? $data->title : null;
        $genre = $data->genre !== '' ? $data->genre : null;
    
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "library";
    
        $key = 'key';
        $jwt = $data->token;
    
        try {
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
    
            if (!isset($decoded->data->access_level) || $decoded->data->access_level !== 'admin') {
                $response->getBody()->write(
                    json_encode((object)[
                        "status" => "fail",
                        "data" => (object)[
                            "Message" => "Access denied, only admins can update books."
                        ]
                    ])
                );
                return $response;
            }
    
            try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                $userid = $decoded->data->userid;
                $access_level = $decoded->data->access_level;
    
                $sql = "SELECT username, password, token FROM users WHERE userid = :userid";
                $statement = $conn->prepare($sql);
                $statement->execute(['userid' => $userid]);
                $userInfo = $statement->fetch(PDO::FETCH_ASSOC);
    
                if ($userInfo['token'] !== $jwt) {
                    $response->getBody()->write(
                        json_encode((object)[
                            "status" => "fail",
                            "data" => (object)[
                                "Message" => "Invalid or Outdated Token."
                            ]
                        ])
                    );
                    return $response;
                }
    
                $sql = "SELECT * FROM books WHERE bookCode = :bookCode";
                $statement = $conn->prepare($sql);
                $statement->execute(['bookCode' => $bookCode]);
                $existing_book = $statement->fetch(PDO::FETCH_ASSOC);
    
                if (!$existing_book) {
                    $response->getBody()->write(
                        json_encode((object)[
                            "status" => "fail",
                            "data" => (object)[
                                "Message" => "Invalid Book Code."
                            ]
                        ])
                    );
                    return $response;
                }
    
                if ($author !== null) {
                    $sql = "SELECT authorid FROM authors WHERE authorname = :author";
                    $statement = $conn->prepare($sql);
                    $statement->execute(['author' => $author]);
                    $existing_author = $statement->fetch(PDO::FETCH_ASSOC);
    
                    if (!$existing_author) {
                        $sql = "INSERT INTO authors (authorname) VALUES (:author)";
                        $statement = $conn->prepare($sql);
                        $statement->execute(['author' => $author]);
                        $authorid = $conn->lastInsertId();
                    } else {
                        $authorid = $existing_author['authorid'];
                    }
                } else {
                    $authorid = $existing_book['authorid'];
                }
    
                $fields = [];
                $newValues = [];
    
                if ($title !== null) {
                    $fields[] = "title = :title";
                    $newValues[':title'] = $title;
                }
    
                if ($genre !== null) {
                    $fields[] = "genre = :genre";
                    $newValues[':genre'] = $genre;
                }
    
                if ($authorid !== null) {
                    $fields[] = "authorid = :authorid";
                    $newValues[':authorid'] = $authorid;
                }
    
                if (empty($fields)) {
                    $response->getBody()->write(
                        json_encode((object)[
                            "status" => "fail",
                            "data" => (object)[
                                "Message" => "No fields to update."
                            ]
                        ])
                    );
                    return $response;
                }
    
                $sql = "UPDATE books SET " . implode(", ", $fields) . " WHERE bookCode = :bookCode";
                $statement = $conn->prepare($sql);
    
                foreach ($newValues as $param => $value) {
                    $statement->bindValue($param, $value);
                }
                $statement->bindValue(':bookCode', $bookCode);
    
                $statement->execute();
    
                $key = 'key';
                $expire = time();
    
                $payload = [
                    'iss' => 'http://library.org',
                    'aud' => 'http://library.com',
                    'iat' => $expire,
                    'exp' => $expire + 3600,
                    'data' => (object)[
                        'userid' => $userid,
                        "name" => $username,
                        "access_level" => $access_level
                    ]
                ];
    
                $new_jwt = JWT::encode($payload, $key, 'HS256');
    
                $sql = "UPDATE users SET token = :token  WHERE userid = :userid";
                $statement = $conn->prepare($sql);
                $statement->execute(['token' => $new_jwt, 'userid' => $userid]);
    
                $response->getBody()->write(
                    json_encode((object)[
                        "status" => "success",
                        "new_token" => $new_jwt
                    ])
                );
    
            } catch (PDOException $e) {
                $response->getBody()->write(json_encode((object)[
                    "status" => "fail",
                    "data" => (object)[
                        "Message" => $e->getMessage()
                    ]
                ]));
            }
        } catch (Exception $e) {
            $response->getBody()->write(json_encode((object)[
                "status" => "fail",
                "data" => (object)[
                    "Message" => $e->getMessage()
                ]
            ]));
        }
    
        $conn = null;
        return $response;
    });    
    

    //Delete Book (Admin)
    $app->delete("/delete/books", function(Request $request, Response $response, array $args) {
        $data = json_decode($request->getBody());
    
        $bookCode = $data->bookCode;
    
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "library";
    
        $key = 'key';
        $jwt = $data->token;
    
        try {
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
    
            if (!isset($decoded->data->access_level) || $decoded->data->access_level !== 'admin') {
                $response->getBody()->write(
                    json_encode(array("status" => "fail", "data" => array("Message" => "Access denied, only admins can delete books.")))
                );
                return $response;
            }
    
            try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $userid = $decoded->data->userid;
                $access_level = $decoded->data->access_level;

                $sql = "SELECT username, password, token FROM users WHERE userid = :userid";
                $statement = $conn->prepare($sql);
                $statement->execute(['userid' => $userid]);
                $userInfo = $statement->fetch(PDO::FETCH_ASSOC);

                if ($userInfo['token'] !== $jwt) {
                    $response->getBody()->write(
                        json_encode(array("status" => "fail", "data" => array("Message" => "Invalid or Outdated Token.")))
                    );
                    return $response;
                }
    
                $sql = "SELECT * FROM books WHERE bookCode = :bookCode";
                $statement = $conn->prepare($sql);
                $statement->execute(['bookCode' => $bookCode]);
                $existing_book = $statement->fetch(PDO::FETCH_ASSOC);
    
                if ($existing_book) {
                    $sql = "DELETE FROM books WHERE bookCode = :bookCode";
                    $statement = $conn->prepare($sql);
                    $statement->execute(['bookCode' => $bookCode]);

                    $key = 'key';
                    $expire = time();

                    $payload = [
                        'iss' => 'http://library.org',
                        'aud' => 'http://library.com',
                        'iat' => $expire,
                        'exp' => $expire + 3600,
                        'data' => array(
                            'userid' => $userid, 
                            "name" => $username,
                            "access_level" => $access_level
                        )
                    ];

                    $new_jwt = JWT::encode($payload, $key, 'HS256');

                    $sql = "UPDATE users SET token = :token  WHERE userid = :userid";
                    $statement = $conn->prepare($sql);
                    $statement->execute(['token' => $new_jwt, 'userid' => $userid]);

                    $response->getBody()->write(
                        json_encode(array("status" => "success", "new_token" => $new_jwt))
                    );

                } else {
                    $response->getBody()->write(
                        json_encode(array("status" => "fail", "data" => array("Message" => "Invalid Book Code.")))
                    );
                    return $response;
                }
            } catch (PDOException $e) {
                $response->getBody()->write(json_encode(array("status" => "fail", "data" => array("Message" => $e->getMessage()))));
            }
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(array("status" => "fail", "data" => array("Message" => $e->getMessage()))));
        }
    
        $conn = null;
        return $response;
    });

    //Display all Books
    $app->get("/displayall/books", function (Request $request, Response $response, array $args) {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "library";

        $key ='key';
        $data=json_decode($request->getBody());
        $jwt=$data->token;

        try {
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
    
            try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $userid = $decoded->data->userid;
                $access_level = $decoded->data->access_level;

                $sql = "SELECT username, password, token FROM users WHERE userid = :userid";
                $statement = $conn->prepare($sql);
                $statement->execute(['userid' => $userid]);
                $userInfo = $statement->fetch(PDO::FETCH_ASSOC);

                if ($userInfo['token'] !== $jwt) {
                    $response->getBody()->write(
                        json_encode(array("status" => "fail", "data" => array("Message" => "Invalid or Outdated Token.")))
                    );
                    return $response;
                }
    
                $sql = "
                    SELECT 
                        books.bookid, 
                        books.title, 
                        books.genre, 
                        books.bookCode, 
                        authors.authorid, 
                        authors.authorname
                    FROM 
                        books_collection
                    JOIN 
                        books ON books_collection.bookid = books.bookid
                    JOIN 
                        authors ON books_collection.authorid = authors.authorid
                ";

                $statement = $conn->query($sql);
                $booksCount = $statement->rowCount();
                $displayBooks = $statement->fetchAll(PDO::FETCH_ASSOC);

                if ($booksCount > 0) {
                    $key = 'key';
                    $expire = time();

                    $payload = [
                        'iss' => 'http://library.org',
                        'aud' => 'http://library.com',
                        'iat' => $expire,
                        'exp' => $expire + 3600,
                        'data' => array(
                            'userid' => $userid, 
                            "name" => $username,
                            "access_level" => $access_level
                        )
                    ];

                    $new_jwt = JWT::encode($payload, $key, 'HS256');

                    $sql = "UPDATE users SET token = :token  WHERE userid = :userid";
                    $statement = $conn->prepare($sql);
                    $statement->execute(['token' => $new_jwt, 'userid' => $userid]);

                    $response->getBody()->write(
                        json_encode(array("status" => "success", "new_token" => $new_jwt, "data" => $displayBooks))
                    );
                } else {
                    $response->getBody()->write(json_encode(array("status" => "success", "data" => "No books found.")));
                }

            } catch (PDOException $e) {
                $response->getBody()->write(json_encode(array("status" => "fail", "data" => array("title" => $e->getMessage()))));
            }
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(array("status" => "fail", "data" => array("title" => $e->getMessage()))));
        }
    
        $conn = null;
        return $response;
    }); 

    //Display Books by author
    $app->get("/display/authorsbooks", function (Request $request, Response $response, array $args) {
        $data=json_decode($request->getBody());
        
        $authorname = $data->authorname;

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "library";

        $key ='key';
        $jwt=$data->token;

        try {
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
    
            try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $userid = $decoded->data->userid;
                $access_level = $decoded->data->access_level;

                $sql = "SELECT username, password, token FROM users WHERE userid = :userid";
                $statement = $conn->prepare($sql);
                $statement->execute(['userid' => $userid]);
                $userInfo = $statement->fetch(PDO::FETCH_ASSOC);

                if ($userInfo['token'] !== $jwt) {
                    $response->getBody()->write(
                        json_encode(array("status" => "fail", "data" => array("Message" => "Invalid or Outdated Token.")))
                    );
                    return $response;
                }
    
                $sql = "
                    SELECT 
                        books.bookid, 
                        books.title, 
                        books.genre,
                        books.bookCode,  
                        authors.authorid, 
                        authors.authorname
                    FROM 
                        books_collection
                    JOIN 
                        books ON books_collection.bookid = books.bookid
                    JOIN 
                        authors ON books_collection.authorid = authors.authorid
                    WHERE
                        authors.authorname = :authorname
                ";

                $statement = $conn->prepare($sql);
                $statement->execute(['authorname'=>$authorname]);
                $booksCount = $statement->rowCount();

                if ($booksCount > 0) {
                    $displayBooks = $statement->fetchAll(PDO::FETCH_ASSOC);

                    $key = 'key';
                    $expire = time();

                    $payload = [
                        'iss' => 'http://library.org',
                        'aud' => 'http://library.com',
                        'iat' => $expire,
                        'exp' => $expire + 3600,
                        'data' => array(
                            'userid' => $userid, 
                            "name" => $username,
                            "access_level" => $access_level
                        )
                    ];

                    $new_jwt = JWT::encode($payload, $key, 'HS256');

                    $sql = "UPDATE users SET token = :token  WHERE userid = :userid";
                    $statement = $conn->prepare($sql);
                    $statement->execute(['token' => $new_jwt, 'userid' => $userid]);

                    $response->getBody()->write(
                        json_encode(array("status" => "success", "new_token" => $new_jwt, "data" => $displayBooks))
                    );
                } else {
                    $response->getBody()->write(
                        json_encode(array("status" => "fail", "data" => array("Message" => "No such author exists.")))
                    );
                }

            } catch (PDOException $e) {
                $response->getBody()->write(json_encode(array("status" => "fail", "data" => array("Message" => $e->getMessage()))));
            }
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(array("status" => "fail", "data" => array("Message" => $e->getMessage()))));
        }
    
        $conn = null;
        return $response;
    });

    //Display Books by title
    $app->get("/display/titlebooks", function (Request $request, Response $response, array $args) {
        $data=json_decode($request->getBody());
        
        $booktitle = $data->booktitle;

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "library";

        $key ='key';
        $jwt=$data->token;

        try {
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
    
            try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $userid = $decoded->data->userid;
                $access_level = $decoded->data->access_level;

                $sql = "SELECT username, password, token FROM users WHERE userid = :userid";
                $statement = $conn->prepare($sql);
                $statement->execute(['userid' => $userid]);
                $userInfo = $statement->fetch(PDO::FETCH_ASSOC);

                if ($userInfo['token'] !== $jwt) {
                    $response->getBody()->write(
                        json_encode(array("status" => "fail", "data" => array("Message" => "Invalid or Outdated Token.")))
                    );
                    return $response;
                }
    
                $sql = "
                    SELECT 
                        books.bookid, 
                        books.title, 
                        books.genre, 
                        books.bookCode,
                        authors.authorid, 
                        authors.authorname
                    FROM 
                        books_collection
                    JOIN 
                        books ON books_collection.bookid = books.bookid
                    JOIN 
                        authors ON books_collection.authorid = authors.authorid
                    WHERE
                        books.title = :booktitle
                ";

                $statement = $conn->prepare($sql);
                $statement->execute(['booktitle'=>$booktitle]);
                $booksCount = $statement->rowCount();

                if ($booksCount > 0) {
                    $displayBooks = $statement->fetchAll(PDO::FETCH_ASSOC);

                    $key = 'key';
                    $expire = time();

                    $payload = [
                        'iss' => 'http://library.org',
                        'aud' => 'http://library.com',
                        'iat' => $expire,
                        'exp' => $expire + 3600,
                        'data' => array(
                            'userid' => $userid, 
                            "name" => $username,
                            "access_level" => $access_level
                        )
                    ];

                    $new_jwt = JWT::encode($payload, $key, 'HS256');

                    $sql = "UPDATE users SET token = :token  WHERE userid = :userid";
                    $statement = $conn->prepare($sql);
                    $statement->execute(['token' => $new_jwt, 'userid' => $userid]);

                    $response->getBody()->write(
                        json_encode(array("status" => "success", "new_token" => $new_jwt, "data" => $displayBooks))
                    );

                } else {
                    $response->getBody()->write(
                        json_encode(array("status" => "fail", "data" => array("Message" => "No such book title exists.")))
                    );
                }

            } catch (PDOException $e) {
                $response->getBody()->write(json_encode(array("status" => "fail", "data" => array("Message" => $e->getMessage()))));
            }
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(array("status" => "fail", "data" => array("Message" => $e->getMessage()))));
        }
    
        $conn = null;
        return $response;
    });

    //Display Books by genre
    $app->get("/display/genrebooks", function (Request $request, Response $response, array $args) {
        $data=json_decode($request->getBody());
        
        $bookgenre = $data->bookgenre;

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "library";

        $key ='key';
        $jwt=$data->token;

        try {
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
    
            try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $userid = $decoded->data->userid;
                $access_level = $decoded->data->access_level;

                $sql = "SELECT username, password, token FROM users WHERE userid = :userid";
                $statement = $conn->prepare($sql);
                $statement->execute(['userid' => $userid]);
                $userInfo = $statement->fetch(PDO::FETCH_ASSOC);

                if ($userInfo['token'] !== $jwt) {
                    $response->getBody()->write(
                        json_encode(array("status" => "fail", "data" => array("Message" => "Invalid or Outdated Token.")))
                    );
                    return $response;
                }
    
                $sql = "
                    SELECT 
                        books.bookid, 
                        books.title, 
                        books.genre, 
                        books.bookCode,
                        authors.authorid, 
                        authors.authorname
                    FROM 
                        books_collection
                    JOIN 
                        books ON books_collection.bookid = books.bookid
                    JOIN 
                        authors ON books_collection.authorid = authors.authorid
                    WHERE
                        books.genre = :bookgenre
                ";

                $statement = $conn->prepare($sql);
                $statement->execute(['bookgenre'=>$bookgenre]);
                $booksCount = $statement->rowCount();

                if ($booksCount > 0) {
                    $displayBooks = $statement->fetchAll(PDO::FETCH_ASSOC);

                    $key = 'key';
                    $expire = time();

                    $payload = [
                        'iss' => 'http://library.org',
                        'aud' => 'http://library.com',
                        'iat' => $expire,
                        'exp' => $expire + 3600,
                        'data' => array(
                            'userid' => $userid, 
                            "name" => $username,
                            "access_level" => $access_level
                        )
                    ];

                    $new_jwt = JWT::encode($payload, $key, 'HS256');

                    $sql = "UPDATE users SET token = :token  WHERE userid = :userid";
                    $statement = $conn->prepare($sql);
                    $statement->execute(['token' => $new_jwt, 'userid' => $userid]);

                    $response->getBody()->write(
                        json_encode(array("status" => "success", "new_token" => $new_jwt, "data" => $displayBooks))
                    );
                } else {
                    $response->getBody()->write(
                        json_encode(array("status" => "fail", "data" => array("Message" => "No such book genre exists.")))
                    );
                }

            } catch (PDOException $e) {
                $response->getBody()->write(json_encode(array("status" => "fail", "data" => array("Message" => $e->getMessage()))));
            }
        } catch (Exception $e) {
            $response->getBody()->write(json_encode(array("status" => "fail", "data" => array("Message" => $e->getMessage()))));
        }
    
        $conn = null;
        return $response;
    });

    $app->run();

?>
