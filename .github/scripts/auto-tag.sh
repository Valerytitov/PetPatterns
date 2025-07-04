#!/bin/bash

# Какой тип инкремента? (по умолчанию patch)
bump=${1:-patch}

latest_tag=$(git tag --list 'v*' --sort=-v:refname | head -n1)

if [[ -z "$latest_tag" ]]; then
  major=1; minor=0; patch=0
else
  # Удаляем все ведущие v (на всякий случай)
  clean_tag=$(echo "$latest_tag" | sed 's/^v*//')
  IFS='.' read -r major minor patch <<<"$clean_tag"
fi

case "$bump" in
  major)
    major=$((major + 1))
    minor=0
    patch=0
    ;;
  minor)
    minor=$((minor + 1))
    patch=0
    ;;
  patch|*)
    patch=$((patch + 1))
    ;;
esac

new_tag="v${major}.${minor}.${patch}"
echo "Next tag: $new_tag"

if git rev-parse "$new_tag" >/dev/null 2>&1; then
  echo "Tag $new_tag already exists, skipping."
else
  git config user.name "github-actions[bot]"
  git config user.email "github-actions[bot]@users.noreply.github.com"
  git tag "$new_tag"
  git push origin "$new_tag"
fi 