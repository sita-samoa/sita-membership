#!/bin/bash

# Check if Python 3 is installed
if ! command -v python3 &> /dev/null; then
    echo "Python 3 is not installed. Please install Python 3 first."
    exit 1
fi

# Check if pip is installed
if ! command -v pip3 &> /dev/null; then
    echo "pip for Python 3 is not installed. Installing pip..."
    curl https://bootstrap.pypa.io/get-pip.py -o get-pip.py
    python3 get-pip.py
    rm get-pip.py
fi

# Install git-revise
echo "Installing git-revise..."
pip3 install git-revise

# Verify the installation
if command -v git-revise &> /dev/null; then
    echo "git-revise has been successfully installed."
else
    echo "There was an issue installing git-revise."
    exit 1
fi
