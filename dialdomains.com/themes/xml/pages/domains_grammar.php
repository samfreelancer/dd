<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<?php $separatorReadings = array(
  '.' => 'dot',
  '/' => 'slash'
); ?>
<grammar xmlns="http://www.w3.org/2001/06/grammar"
         version="1.0" 
         mode="voice" 
         xml:lang="en"
         type="application/srgs+xml" 
         tag-format="semantics/1.0" 
         root="domain">
  <rule id="domain">
      <one-of>
        <item repeat="0-1">
          h t t p
        </item>
        <item repeat="0-1">
          h t t p s
        </item>
      </one-of>
    <item repeat="0-1">
      w w w
    </item>
    <one-of>
      <?php foreach ($domainsWithReadings as $domainWithReading): ?>
      <item>
        <one-of>
          <?php foreach($domainWithReading['readings'] as $readingParts): ?>
            <item>
              <?php foreach($readingParts as $readingPart): ?>
                <?php if (!empty($readingPart['optional']) && $readingPart['optional']): ?>
                  <item repeat="0-1">
                    <?php echo $readingPart['part']; ?>
                  </item>
                <?php else: ?>
                  <?php echo $readingPart['part']; ?>
                <?php endif; ?>
                <?php if (!empty($readingPart['separator']) && !empty($separatorReadings[$readingPart['separator']])): ?>
                  <item repeat="0-1">
                    <?php echo $separatorReadings[$readingPart['separator']]; ?>
                  </item>
                <?php endif; ?>
              <?php endforeach; ?>
            </item>
          <?php endforeach; ?>
        </one-of>
        <tag>out.domain = "<?php echo $domainWithReading['domain']; ?>";</tag>
        <tag>out.phone = "+1<?php echo $domainWithReading['phone_number']; ?>";</tag>
      </item>
      <?php endforeach; ?>
    </one-of>
  </rule>
</grammar>