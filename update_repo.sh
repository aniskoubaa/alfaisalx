#!/bin/bash

# Ensure we're in the repository root directory
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd "$DIR"

echo "====================================="
echo "🔄 Updating AlfaisalX Repository 🔄"
echo "====================================="

# Check if there are any uncommitted changes
if ! git diff-index --quiet HEAD --; then
    echo "⚠️ Warning: You have uncommitted changes."
    echo "Stashing changes before pulling..."
    git stash
    STASHED=1
else
    STASHED=0
fi

# Pull the latest changes from the origin
echo "📥 Pulling latest changes from the main branch..."
git fetch origin main
git pull origin main

# Restore stashed changes if there were any
if [ $STASHED -eq 1 ]; then
    echo "📦 Restoring local changes..."
    git stash pop
    echo "⚠️ Please resolve any merge conflicts if they occurred."
fi

# Optional: Set appropriate permissions
echo "🔒 Setting file permissions..."
chmod +x update_repo.sh 2>/dev/null

echo "✅ Update complete!"
echo "====================================="
