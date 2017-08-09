<?php

class domain extends adfModelBase {

    public function serializeReadings($readings) {
        return serialize($readings);
    }

    public function unserializeReading($readings) {
        return unserialize($readings);
    }

    # should be getDomainByName, more reuseable

    public function getDomainByName($domain) {
        return false !== $this->getOneByField('domain', $domain);
    }

    # what are readings? similar domains?

    public function generateReadings($domain, $partialStateId = null) {

        // todo state
        $readingsGenerator = new ReadingsGenerator($domain);
        $readings = $readingsGenerator->getReadings();
        return $readings;
    }

    public function getDomainsWithReadingsForGrammar() {
        $domainPartReadingM = new domainReadingPart();

        $domains = $this->getAllWhere("is_voice_domain = 1");
        $domainsWithReadings = array();
        foreach ($domains as $domain) {
            $domainsWithReadings[] = array(
                'domain' => $domain['domain'],
            	'phone_number' => $domain['phone_number'],
                'readings' => $domainPartReadingM->getReadingsForGrammarByDomain($domain['id'])
            );
        }
        return $domainsWithReadings;
    }

    # what does this do?

    public function addWithReadings($data, $readings = null) {
        $this->db->setAutocommit(false);
        $this->db->startTransaction();
        try {
            if ($this->getDomainByName($data['domain'])) {
                throw new Exception("Domain already registered");
            }
            $domainReadingPartM = new domainReadingPart();
            $parsedReadings = array();
            if (is_null($readings) || empty($readings)) {
                $readings = $this->generateReadings($data['domain']);
            }
            foreach ($readings as $reading) {
                if ($domainReadingPartM->isReadingUsed($reading)) {
                    throw new Exception("Reading " . $reading . " is already in use");
                }
                $parsedReading = ReadingsGenerator::getPartsFromReading($reading);
                $parsedReadings[] = $parsedReading;
            }
            if (false !== $newDomainId = $this->add($data)) {
                $domainReadingPartM = new domainReadingPart();
                foreach ($parsedReadings as $readingOrder => $parsedReading) {
                    if (false === $domainReadingPartM->addNewReading($newDomainId, $readingOrder, $parsedReading)) {
                        throw new Exception($domainReadingPartM->getError());
                    }
                }
            } else {
                throw new Exception($this->GetError());
            }
            $this->db->commit();
            $this->db->setAutocommit(true);

            return $newDomainId;
        } catch (Exception $ex) {
            $this->setError($ex->getMessage());
            $this->db->rollback();
            $this->db->setAutocommit(true);
            return false;
        }
    }

    public function getDomainsByUserId($userId, $orderBy, $sort) {
        if (false !== $domains = $this->getManyByField('user_id', $userId, $orderBy, $sort)) {
            return $domains;
        } else {
            return false;
        }
    }

    public function getUserUnregisteredDomainsByIdArray($ids, $userId) {
        $in = '(';
        foreach ($ids as $key => $value) {
            $in.= $value . ',';
        }
        $in = trim($in, ",");
        $in.= ')';
        if (false !== $domains = $domains = $this->getAllWhere("user_id=" . $userId . " AND registered=0 AND  id IN " . $in)) {
            return $domains;
        } else {
            return false;
        }
    }

    public function getAvailableTlds() {
        return array('com', 'net', 'org', 'info', 'biz', 'mobi', 'me', 'tv', 'com.mx', 'us', 'tel', 'im', 'in', 'cc', 'ws', 'name', 'mx', 'be', 'de', 'so', 'co.nz', 'co', 'co.uk', 'co.in');
    }


}
