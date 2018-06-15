<?php

namespace TR\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Google_Client;
use Google_Service_Sheets;
use TR\PlatformBundle\Entity\Vocabulary;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;


class PlatformController extends Controller
{
    public function indexAction(Request $request)
    {
        return $this->render('TRPlatformBundle::index.html.twig');
    }

    public function irregularVerbsAction(Request $request)
    {
        return $this->render('TRPlatformBundle:exercices:irregular_verbs.html.twig');
    }

    public function exerciceAAction(Request $request)
    {
        // Get the API client and construct the service object.
        $client = self::getClient();
        $service = new Google_Service_Sheets($client);

        // Prints the names and majors of students in a sample spreadsheet:
        // https://docs.google.com/spreadsheets/d/1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms/edit
        $spreadsheetId = '1l008XVwuSa4hhMW95eBtTZ7HdbmwSHIYWvqfH63VPGg';
        $range = 'A1:D';
        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();

        /*Ajout du tableau en base de donnée*/
        $entityManager = $this->getDoctrine()->getManager();

        foreach ($values as $rows) {
            $exists = $entityManager->getRepository('TR\PlatformBundle\Entity\Vocabulary')->findBy(array(
                'english' => $rows[0], 
                'french' => $rows[1]
            ));
            
            if(!$exists) {
                $examples = null;
                $i = 2;
                while( isset($rows[$i]) ) {
                    if ( $i == 2 ) {
                        $examples = $rows[2];
                    } else {
                        $examples .= " | ".$rows[$i];
                    }
                    $i++;
                }

                $vocabulary = new Vocabulary($rows[0], $rows[1], $examples);

                $entityManager->persist($vocabulary);
            }
        }              

        $entityManager->flush();
        
        return $this->render('TRPlatformBundle:exercices:exercice_a.html.twig', array(
            'words' => $values
        ));
    }

    function getClient()
    {
        $client = new Google_Client();
        $client->setApplicationName('ToolReview');
        $client->setScopes(Google_Service_Sheets::SPREADSHEETS_READONLY);
        $client->setAuthConfig('client_secret.json');
        $client->setAccessType('offline');

        // Load previously authorized credentials from a file.
        $credentialsPath = self::expandHomeDirectory('credentials.json');
        if (file_exists($credentialsPath)) {
            $accessToken = json_decode(file_get_contents($credentialsPath), true);
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            $authCode = "4/AACHrM8N4ocdfWwhIH63nFEJlnx-sepST6OzWhlZp0dTV2R310OsqH0";

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

            // Store the credentials to disk.
            if (!file_exists(dirname($credentialsPath))) {
                mkdir(dirname($credentialsPath), 0700, true);
            }
            file_put_contents($credentialsPath, json_encode($accessToken));
            printf("Credentials saved to %s\n", $credentialsPath);
        }
        $client->setAccessToken($accessToken);

        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
        }
        return $client;
    }

    function expandHomeDirectory($path)
    {
        $homeDirectory = getenv('HOME');
        if (empty($homeDirectory)) {
            $homeDirectory = getenv('HOMEDRIVE') . getenv('HOMEPATH');
        }
        return str_replace('~', realpath($homeDirectory), $path);
    }

}