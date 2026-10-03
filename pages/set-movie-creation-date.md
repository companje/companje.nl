```bash
#!/bin/bash
# macOS: zet de bestand-aanmaakdatum op de ingebedde opnamedatum.
# Zonder argumenten: alleen TEST.MOV naast dit script.
# --all: alle films direct in de scriptmap; --dry-run: alleen tonen.
# Andere argumenten: expliciete bestandspaden.
set -euo pipefail

command -v exiftool >/dev/null || { echo 'ExifTool ontbreekt (brew install exiftool).' >&2; exit 1; }
[[ $(uname -s) == Darwin ]] || { echo 'Dit script vereist macOS.' >&2; exit 1; }
script_dir=$(cd -- "$(dirname -- "$0")" && pwd)
dry_run=false
all=false
files=()
for arg in "$@"; do
    case "$arg" in
        --dry-run) dry_run=true ;;
        --all) all=true ;;
        -h|--help)
            echo "Gebruik: $0 [--dry-run] [--all | bestand ...]"
            echo 'Standaard: alleen TEST.MOV. --all: films naast het script, zonder submappen.'
            exit 0 ;;
        -*) echo "Onbekende optie: $arg" >&2; exit 1 ;;
        *) files+=("$arg") ;;
    esac
done
if $all; then
    [[ ${#files[@]} -eq 0 ]] || { echo '--all niet combineren met bestandspaden.' >&2; exit 1; }
    shopt -s nullglob nocaseglob
    files=("$script_dir"/*.{mov,mp4,m4v,avi,mkv,mts,m2ts,3gp,webm,mpg,mpeg})
elif [[ ${#files[@]} -eq 0 ]]; then
    files=("$script_dir/TEST.MOV")
fi

status=0
for file in "${files[@]}"; do
    [[ "$file" = /* ]] || file="$PWD/$file"
    if [[ ! -f "$file" || -L "$file" ]]; then
        echo "Overgeslagen (geen regulier bestand of een symlink): $file" >&2
        status=1
        continue
    fi
    chosen=''
    # Apple-opnamedatum met tijdzone eerst. QuickTime-getallen zijn UTC;
    # QuickTimeUTC converteert die naar de lokale tijdzone, inclusief zomertijd.
    for tag in Keys:CreationDate UserData:DateTimeOriginal EXIF:DateTimeOriginal QuickTime:CreateDate; do
        if ! value=$(exiftool -api QuickTimeUTC=1 -s3 "-$tag" "$file"); then
            status=1
            break
        fi
        if [[ "$value" =~ ^[12][0-9]{3}:[0-9]{2}:[0-9]{2}\ [0-9]{2}:[0-9]{2}:[0-9]{2} ]]; then
            chosen="$value"
            break
        fi
    done
    if [[ -z "$chosen" ]]; then
        echo "Overgeslagen (geen bruikbare opnamedatum): $file"
        continue
    fi
    printf '%s -> %s [%s]\n' "${file##*/}" "$chosen" "$tag"
    if ! $dry_run; then
        # Alleen het filesystem-attribuut schrijven; de video en ingebedde
        # metadata blijven intact. FileModifyDate wordt niet ingesteld.
        if ! exiftool "-FileCreateDate=$chosen" "$file"; then
            status=1
        fi
    fi
done
exit "$status"
```
