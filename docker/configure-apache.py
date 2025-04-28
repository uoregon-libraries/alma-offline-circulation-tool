#!/usr/bin/env python3
#
# This script replaces LDAP-related variables in the apache config at container
# startup. It is almost entirely AI-written.
import os
import sys

TEMPLATE_FILE = "/etc/httpd/conf.d/app.conf.template"
CONFIG_FILE = "/etc/httpd/conf.d/app.conf"

def generate_config():
    """Generates the Apache config file from the template and environment variables."""
    try:
        with open(TEMPLATE_FILE, 'r') as f:
            template_content = f.read()
    except FileNotFoundError:
        print(f"Error: Template file not found at {TEMPLATE_FILE}", file=sys.stderr)
        sys.exit(1)
    except IOError as e:
        print(f"Error reading template file {TEMPLATE_FILE}: {e}", file=sys.stderr)
        sys.exit(1)

    ldap_enabled = os.environ.get('LDAP_ENABLED', 'false').lower() == 'true'

    if ldap_enabled:
        print("LDAP authentication enabled. Processing template.")

        # Check for mandatory variables
        bind_dn = os.environ.get('LDAP_BIND_DN')
        bind_password = os.environ.get('LDAP_BIND_PASSWORD')
        ldap_url = os.environ.get('LDAP_URL')
        require_groups_str = os.environ.get('LDAP_REQUIRE_GROUPS')

        if not all([bind_dn, bind_password, ldap_url, require_groups_str]):
            print("Error: Missing one or more required LDAP environment variables: "
                  "LDAP_BIND_DN, LDAP_BIND_PASSWORD, LDAP_URL, LDAP_REQUIRE_GROUPS", file=sys.stderr)
            sys.exit(1)

        # Generate 'Require ldap-group' lines
        require_lines = '\n'.join([f"Require ldap-group {group.strip()}"
                                   for group in require_groups_str.split('|') if group.strip()])

        # Perform substitutions
        config_content = template_content.replace('__LDAP_BIND_DN__', bind_dn)
        config_content = config_content.replace('__LDAP_BIND_PASSWORD__', bind_password)
        config_content = config_content.replace('__LDAP_URL__', ldap_url)
        config_content = config_content.replace('# __LDAP_REQUIRE_GROUPS__', require_lines)
        # Remove the markers
        config_content = config_content.replace('# __LDAP_ENABLED_START__\n', '')
        config_content = config_content.replace('# __LDAP_ENABLED_END__\n', '')

        print("Generated Apache config with LDAP:")

    else:
        print("LDAP authentication disabled. Removing LDAP config block.")
        # Remove the entire LDAP block using markers
        start_marker = '# __LDAP_ENABLED_START__'
        end_marker = '# __LDAP_ENABLED_END__'
        start_index = template_content.find(start_marker)
        end_index = template_content.find(end_marker)

        if start_index != -1 and end_index != -1:
            # Include the end marker line in removal
            end_index += len(end_marker)
            # Adjust for potential newline after end marker
            if end_index < len(template_content) and template_content[end_index] == '\n':
                end_index += 1
            config_content = template_content[:start_index] + template_content[end_index:]
        else:
            # Markers not found, use the template as is (minus markers if they exist individually)
            print("Warning: LDAP block markers not found in template.", file=sys.stderr)
            config_content = template_content.replace(start_marker + '\n', '').replace(end_marker + '\n', '')


        print("Generated Apache config without LDAP:")

    try:
        with open(CONFIG_FILE, 'w') as f:
            f.write(config_content)
        print(config_content) # Print generated config to stdout for verification
    except IOError as e:
        print(f"Error writing config file {CONFIG_FILE}: {e}", file=sys.stderr)
        sys.exit(1)

if __name__ == "__main__":
    generate_config()
