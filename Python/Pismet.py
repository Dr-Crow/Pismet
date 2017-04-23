import os
import time
import xml.etree.ElementTree as ET
import pymysql
from datetime import datetime as dt
import glob

while True:
    # Kill the Kismet Running, thus creating the log file
    Password = 'Password'
    command1 = '/etc/init.d/kismet stop'
    os.system('echo %s|sudo -S %s' % (Password, command1))

    # Sleep 3 seconds before restarting the Kismet Scan
    time.sleep(3)

    list_of_files = glob.glob('/home/pi/kismet/*')  # * means all if need specific format then *.csv
    latest_file = max(list_of_files, key=os.path.getctime)
    newest = latest_file[16:43]
    # newest = 'Pismet-20170422-19-46-27-1.'
    pismetFile = newest + 'netxml'
    print(pismetFile)

    # Restart Kismet
    command2 = '/etc/init.d/kismet start'
    os.system('echo %s|sudo -S %s' % (Password, command2))

    # Processing of the logs
    tree = ET.parse(pismetFile)
    root = tree.getroot()

    conn = pymysql.connect(host='localhost', port=3306, user='root', passwd='', db='pismet_db')
    cur = conn.cursor()

    print("Pulling Messages.....")
    for network in root.findall('wireless-network'):
        if network.get('type') != 'probe':
            print(network.tag, network.attrib)

            essid = None
            type = None
            info = None
            mac = None
            manuf = None
            carrier = None
            name = None
            network_type = None
            total = None
            discovery_type = None
            dev_name = None
            channel = None

            if network.find('carrier') is not None:
                carrier = network.find('carrier').text
                print('CARRIER: ' + carrier)

            if network.find('channel') is not None:
                channel = network.find('channel').text
                print('CHANNEL: ' + channel)

            if network.find('BSSID') is not None:
                mac = network.find('BSSID').text
                print('MAC: ' + mac)

            for ssid in network.findall('SSID'):
                temp = ssid.find('essid').text
                if temp is None:
                    print('ESSID: NULL')
                else:
                    essid = temp
                    print('ESSID: ' + essid)

                if ssid.find('type') is not None:
                    discovery_type = ssid.find('type').text
                    print('TYPE: ' + discovery_type)

                if ssid.find('wpa-version') is not None:
                    network_type = ssid.find('wpa-version').text
                    print("NetTYPE: " + network_type)

                if ssid.find('info') is not None:
                    info = ssid.find('info').text
                    print('INFO: ' + info)

                if ssid.find('dev-name') is not None:
                    dev_name = ssid.find('dev-name').text
                    print('DEV-NAME: ' + dev_name)

            try:
                cur.execute(
                    ("INSERT INTO networks (mac, essid, discovery_type, info, manuf, carrier, dev_name, network_type) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)"),
                    (mac, essid, discovery_type, info, manuf, carrier, dev_name, network_type)
                )
                conn.commit()

                cur.execute(
                    ("INSERT INTO network_channels (mac, channel) VALUES (%s, %s)"),
                    (mac, channel)
                )
                conn.commit()

                for ssid in network.findall('SSID'):
                    encryption = ssid.findall('encryption')
                    for i in encryption:
                        if i.text != "None":
                            print('ENCRYPTION: ' + i.text[4:])

                            cur.execute(
                                ("INSERT INTO network_encryptions (mac, encryption) VALUES (%s, %s)"),
                                (mac, i.text[4:])
                            )
                            conn.commit()
                        else:
                            print('ENCRYPTION: NULL')

                freq = network.findall('freqmhz')
                for i in freq:
                    temp = i.text
                    temp = temp[:1] + "." + temp[1:4]
                    print('FREQ: ' + temp)
                    cur.execute(
                        ("INSERT INTO network_freqs (mac, freq) VALUES (%s, %s)"),
                        (mac, temp)
                    )
                    conn.commit()
            except:
                print("Network all ready in the DB")

            for packets in network.findall('packets'):
                total = packets.find('total').text
                print('PACKETS: ' + total)

            for snr in network.findall('snr-info'):
                lastSig = snr.find('last_signal_dbm').text
                print('LAST SIG: ' + lastSig)
                lastNoise = snr.find('last_noise_dbm').text
                print('LAST NOISE: ' + lastNoise)
                maxSig = snr.find('max_signal_dbm').text
                print("MAX SIG: " + maxSig)
                maxNoise = snr.find('max_noise_dbm').text
                print("MAX NOISE: " + maxNoise)

                cur.execute(
                    ("INSERT INTO network_snr (mac, seen, packets, last_signal_dbm, last_noise_dbm, max_signal_dbm, max_noise_dbm) VALUES (%s, %s, %s, %s, %s, %s, %s)"),
                    (mac, dt.now(), total, lastSig, lastNoise, maxSig, maxNoise)
                )
                conn.commit()

            for client in network.findall('wireless-client'):
                if mac != client.find('client-mac').text:
                    print('Client: ' + client.get('number'))

                    clientMac = None
                    clientUuid = None
                    clientManuf = None
                    clientCarrier = None
                    clientChannel = None
                    clientTotal = None
                    clientFreq = None

                    if client.find('client-mac') is not None:
                        clientMac = client.find('client-mac').text
                        print('Client MAC: ' + clientMac)

                    for seen in client.findall('seen-card'):
                        clientUuid = seen.find('seen-uuid').text
                        print('UUID: ' + clientUuid)

                    if client.find('manuf') is not None:
                        clientManuf = client.find('manuf').text
                        print('MANUF: ' + clientManuf)

                    if client.find('carrier') is not None:
                        clientCarrier = client.find('carrier').text
                        print('CARRIER: ' + clientCarrier)

                    if client.find('channel') is not None:
                        clientChannel = network.find('channel').text
                        print('CHANNEL: ' + clientChannel)

                    if client.find('freqmhz') is not None:
                        clientFreq = client.find('freqmhz').text
                        clientFreq = clientFreq[:1] + "." + clientFreq[1:4]
                        print('FREQ: ' + clientFreq)

                    for packets in client.findall('packets'):
                        clientTotal = packets.find('total').text
                        print('PACKETS: ' + clientTotal)

                    for snr in client.findall('snr-info'):
                        clientLastSig = snr.find('last_signal_dbm').text
                        print('LAST SIG: ' + clientLastSig)
                        clientLastNoise = snr.find('last_noise_dbm').text
                        print('LAST NOISE: ' + clientLastNoise)
                        clientMaxSig = snr.find('max_signal_dbm').text
                        print("MAX SIG: " + clientMaxSig)
                        clientMaxNoise = snr.find('max_noise_dbm').text
                        print("MAX NOISE: " + clientMaxNoise)

                        try:
                            cur.execute(
                                ("INSERT INTO clients (mac, seen, manuf, carrier, channel, freq, network_mac, packets, last_signal_dbm, last_noise_dbm, max_signal_dbm, max_noise_dbm) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"),
                                (clientMac, dt.now(), clientManuf, clientCarrier, clientChannel, clientFreq, mac, clientTotal, clientLastSig, clientLastNoise, clientMaxSig, clientMaxNoise)
                            )
                            conn.commit()
                        except:
                            print("Repeat Client...")

    cur.close()
    print("Sleeping for 3 mins and then pull again")
    time.sleep(180)


